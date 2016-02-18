<?php

namespace Cvele\MultiTenantBundle\Event\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Cvele\MultiTenantBundle\Model\TenantInterface;
use Cvele\MultiTenantBundle\Model\TenantAwareUserInterface;
use Cvele\MultiTenantBundle\Model\TenantManager;
use Cvele\MultiTenantBundle\Exception\UserHasNoTenantsException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * @author Vladimir Cvetić <vladimir@ferdinand.rs>
 */
class TenantListener implements EventSubscriberInterface
{
  private $tenantManager;
	private $router;
	private $authorizationChecker;
	private $logoutRoute;
	private $currentUser = null;
  private $tokenStorage;

	public function __construct(TenantManager $tenantManager, Router $router, AuthorizationChecker $authorizationChecker, TokenStorage $tokenStorage, $logoutRoute)
	{
		$this->router               = $router;
		$this->authorizationChecker = $authorizationChecker;
		$this->tenantManager        = $tenantManager;
		$this->logoutRoute          = $logoutRoute;
    $this->tokenStorage         = $tokenStorage;
	}

	public function onKernelRequest(GetResponseEvent $event)
	{
		$request = $event->getRequest();
    try {
      $isAuthenticated = $this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY');
    } catch (AuthenticationCredentialsNotFoundException $e) {
      $isAuthenticated = false;
    }

		if ($isAuthenticated) {
			$this->currentUser = $this->tokenStorage->getToken()->getUser();
			if (($this->currentUser instanceof TenantAwareUserInterface) === false) {
				/**
				 * User does not implement TenantInterface we will skip entire process
				 */
				return false;
			}
			if ($this->currentUser->getUserTenants()->count() === 0) {
				throw new UserHasNoTenantsException($this->currentUser);
			}
		} else {
			/**
			 * User is not logged in, skip everything
			 */
			return false;
		}

		/**
		 * Can be satisfied when user is first logged in or when user wants to switch tenant
		 */
		if (!empty($request->get('tenant_id'))) {
			$response = $this->assignTenantIfValid($request->get('tenant_id'), $event);
			if ($response === true) {
				return true;
			}
		}

		/**
		 * User already has tenant in session and has not requested to switch to new tenant.
		 * Check if tenant still exists and that user still has privilegies to access it.
		 */
		if (!empty($request->getSession()->get('tenant_id'))) {
			$response = $this->assignTenantIfValid($request->getSession()->get('tenant_id'), $event);
			if ($response === true) {
				return true;
			}
		}

		if (isset($response) && $response instanceof RedirectResponse) {
			return $response;
		}
	}

	protected function assignTenantIfValid($tenantId, GetResponseEvent $event)
	{
		$tenant = $this->tenantManager->findTenantById((int) $tenantId);
		if ($tenant instanceof TenantInterface) {
			if ($this->userHasTenantPrivilegies($tenant) === true) {
				$request = $event->getRequest();
				$request->getSession()->set('tenant_id', $tenant->getId());
				return true;
			} else {
				/**
				 * User does not have privilegies to access tenent
				 */
				return $this->logoutUser($event);
			}
		}
		return false;
	}

	protected function userHasTenantPrivilegies(TenantInterface $tenant)
	{
		return (boolean) $this->currentUser->getUserTenants()->contains($tenant);
	}

	protected function logoutUser(GetResponseEvent $event)
	{
		$url      = $this->router->generate($this->logoutRoute);
		$response = new RedirectResponse($url);
        $event->setResponse($response);
        return $response;
	}

	public static function getSubscribedEvents()
	{
		return array(
			// must be registered before the default Locale listener
			KernelEvents::REQUEST => [['onKernelRequest', 17]],
		);
	}
}
