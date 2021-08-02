<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GoogleController extends AbstractController
{
    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [ClientRegistry::class];
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/google", name="connect_google")
     *
     * @param ClientRegistry $clientRegistry
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectAction(Request $request, ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect();
    }

    /**
     * Facebook redirects to back here afterwards.
     *
     * @Route("/connect/google/check", name="connect_google_check")
     *
     * @param Request $request
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectCheckAction(Request $request)
    {
        if (!$this->getUser()) {
            return new JsonResponse(array('status' => false, 'message' => 'User not found!'));
        } else {
            if ($this->getUser()->getSucursal()) {
                return $this->redirectToRoute('easyadmin', [
                    'action' => 'list',
                    'entity' => 'OrdenTrabajo',
                ]);
            } else {
                return $this->redirectToRoute('fos_user_profile_edit');
            }
        }
    }
}
