<?php

namespace Cvele\MultiTenantBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserLoginRedirectHandler implements AuthenticationSuccessHandlerInterface
{
    private $context;
    private $router;
    private $pickTenantRoute;
    private $redirectAfterLoginRoute;

    public function __construct(SecurityContext $context, $router, $pickTenantRoute, $redirectAfterLoginRoute)
    {
        $this->context                 = $context;
        $this->router                  = $router;
        $this->redirectAfterLoginRoute = $redirectAfterLoginRoute;
        $this->pickTenantRoute         = $pickTenantRoute;
    }

    public function onAuthenticationSuccess(Request $request,  TokenInterface $token)
    {
        $user = $this->context->getToken()->getUser();
        if ($user->getUserTenants()->count() > 1) {
            $url = $this->router->generate($this->pickTenantRoute);
            $response = new RedirectResponse($url);
        }
        else {
            $request->getSession()->set('tenant_id', $tenants->first()->getId());
            $url = $this->router->generate($this->redirectAfterLoginRoute);
            $response = new RedirectResponse($url);
        }

        return $response;
    }
}
