<?php

namespace Cvele\MultiTenantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class TenantController extends Controller
{
  public function pickTenantAction(Request $request)
  {
      $securityContext = $this->container->get('security.authorization_checker');
      if (!$securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
          throw new AccessDeniedException();
      }
      $user = $securityContext->getToken()->getUser();
      return $this->render('MultiTenantBundle:Tenant:list.html.twig', array(
          'tenants' => $user->getUserTenants()
      ));
  }
}
