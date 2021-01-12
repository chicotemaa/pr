<?php

namespace App\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting.
 */
class UserListener implements EventSubscriberInterface
{
    private $router;
    private $tokenStorage;
    private $em;

    public function __construct(UrlGeneratorInterface $router, TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::PROFILE_EDIT_SUCCESS => ['onProfile', -10],
            FOSUserEvents::REGISTRATION_SUCCESS => ['onRegistrationSuccess', -10],
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        $user->addRole('ROLE_CLIENTE');
        $user->addRole('ROLE_CLIENTE_MENU');
        $user->getCliente()->setSucursal($user->getSucursal());
        $user->getCliente()->setCorreo($user->getEmail());
        $user->getCliente()->setRazonSocial($user->getUsername());

        $url = $this->router->generate('easyadmin', array(
            'action' => 'list',
            'entity' => 'OrdenTrabajo',
        ));

        $event->setResponse(new RedirectResponse($url));
    }

    public function onProfile(FormEvent $event)
    {
        $cliente = $this->getUser()->getCliente();

        if ($cliente) {
            $cliente->setSucursal($this->getUser()->getSucursal());
            $cliente->setCorreo($this->getUser()->getEmail());

            $this->em->persist($cliente);
            $this->em->flush();
        }

        $url = $this->router->generate('easyadmin', array(
            'action' => 'list',
            'entity' => 'OrdenTrabajo',
        ));

        $event->setResponse(new RedirectResponse($url));
    }

    private function getUser()
    {
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
        } else {
            $user = null;
        }

        return $user;
    }
}
