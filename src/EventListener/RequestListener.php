<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RequestListener
{
    private $token;
    private $em;
    private $authorizationChecker;
    private $session;

    public function __construct(
        TokenStorageInterface $token,
        EntityManagerInterface $em,
        AuthorizationCheckerInterface $authorizationChecker,
        SessionInterface $session
    ) {
        $this->token = $token;
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $user = $this->getUser();

        if (
            $user
            && 'anon.' != $user
            && !$this->authorizationChecker->isGranted('ROLE_ADMIN', $user)
        ) {
            // Si es ROLE_EMPLEADO filtrar por usuario
            // Si es ROLE_STAFF filtrar  por cliente id
            // Si es ROLE_ENCARGADO filtrar por sucursal

            if ($this->authorizationChecker->isGranted('ROLE_EMPLEADO', $user)&&) {
                $filter = $this->em->getFilters()->enable('user_filter');
                $filter->setParameter('user_id', $user->getId());
            } elseif ($this->authorizationChecker->isGranted('ROLE_MANAGER', $user)) {
                $filter = $this->em->getFilters()->enable('cliente_filter');
                $filter->setParameter('cliente_id', $user->getCliente()->getId());
            } elseif ($this->authorizationChecker->isGranted('ROLE_FACILITY', $user)) {
                $filter = $this->em->getFilters()->enable('facility_filter');
                $filter->setParameter('facility_id', $user->getFacility()->getId());
            } elseif ($this->authorizationChecker->isGranted('ROLE_STAFF', $user)) {
                $filter = $this->em->getFilters()->enable('sucursal_cliente_filter');
                $filter->setParameter('sucursal_de_cliente_id', $user->getSucursalDeCliente()->getId());
            } elseif ($this->authorizationChecker->isGranted('ROLE_SUCURSAL', $user)) {
                $filter = $this->em->getFilters()->enable('sucursal_filter');
                $filter->setParameter('sucursal_id', $user->getSucursal()->getId());    
            }
        }
    }

    private function getUser()
    {
        if ($this->token->getToken()) {
            $user = $this->token->getToken()->getUser();
        } else {
            $user = null;
        }

        return $user;
    }
}
