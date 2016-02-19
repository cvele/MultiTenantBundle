<?php

namespace Cvele\MultiTenantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class TenantController extends Controller
{
  public function pickTenantAction(Request $request)
  {
      if (!$this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
          throw new AccessDeniedException();
      }
      $user = $this->container->get('security.token_storage')->getToken()->getUser();
      return $this->render('MultiTenantBundle:Tenant:list.html.twig', array(
          'tenants' => $user->getUserTenants(),
          'redirect_route' => $this->getParameter('multi_tenant.redirect_after_login_route')
      ));
  }
}
