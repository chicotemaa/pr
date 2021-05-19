<?php

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use App\Entity\Sucursal;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\FormularioResultado;
use App\Entity\FormularioResultadoExpress;

class PersistListener
{
    private $session;
    private $authorization_checker;
    private $tokenStorage;
    private $em;

    public function __construct(SessionInterface $session, AuthorizationCheckerInterface $authorization_checker, TokenStorageInterface $tokenStorage)
    {
        $this->session = $session;
        $this->authorization_checker = $authorization_checker;
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->em = $event->getEntityManager();

        if ($event->getEntity() instanceof FormularioResultado) {
            $this->prePersitFormularioResultado($event->getEntity());

            return null;

            $this->setSucursalEntity($event->getEntity());

        } else if ($event->getEntity() instanceof FormularioResultadoExpress) {
            $this->prePersitFormularioResultadoExpress($event->getEntity());
            return null;
        } else {
            $this->setSucursalEntity($event->getEntity());
        }
    }

    private function prePersitFormularioResultado($entity)
    {
        $entity->getOrdenTrabajo()->setEstado(4);
    }

    private function prePersitFormularioResultadoExpress($entity)
    {
        $entity->setUser($this->getUser());
    }

    public function setSucursalEntity($entity)
    {
        if (
            $this->getUser() && method_exists($entity, 'getSucursal')
            && !$this->authorization_checker->isGranted('ROLE_CLIENTE')
        ) {
            if (is_null($entity->getSucursal())) {
                $sucursal = $this->em->getPartialReference(
                    Sucursal::class,
                    $this->session->get('user_sucursal')
                );
                $entity->setSucursal($sucursal);
            }
        }
    }

    private function getUser()
    {
        if ($this->tokenStorage->getToken() && 'anon.' !== $this->tokenStorage->getToken()->getUser()) {
            $user = $this->tokenStorage->getToken()->getUser();
        } else {
            $user = null;
        }

        return $user;
    }
}
