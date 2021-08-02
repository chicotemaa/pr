<?php

namespace App\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Listener responsible to change the redirection at the end of the password resetting.
 */
class LoginListener implements EventSubscriberInterface
{
    private $session;
    private $authorization_checker;

    public function __construct(SessionInterface $session, AuthorizationCheckerInterface $authorization_checker)
    {
        $this->session = $session;
        $this->authorization_checker = $authorization_checker;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onSecurityInteractiveLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        );
    }

    public function onSecurityInteractiveLogin($event)
    {
        // FYI
        if ($event instanceof UserEvent) {
            $user = $event->getUser();
        }
        if ($event instanceof InteractiveLoginEvent) {
            $user = $event->getAuthenticationToken()->getUser();
        }
        if (!$this->authorization_checker->isGranted('ROLE_SUPER_ADMIN', $user) && $user->getSucursal()) {
            $this->session->set('user_sucursal', $user->getSucursal()->getId());
        }
    }
}
