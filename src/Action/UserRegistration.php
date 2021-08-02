<?php

namespace App\Action;

use App\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use App\Service\TokenService;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserRegistration
{
    private $security;
    private $userManager;
    private $tokenService;

    public function __construct(UserManagerInterface $userManager, TokenService $tokenService)
    {
        $this->userManager = $userManager;
        $this->tokenService = $tokenService;
    }

    public function __invoke(User $data)
    {
        $existUser = $this->userManager->findUserByEmail($data->getEmail());

        if ($existUser) {
            $data = $existUser;
        } else {
            $data->addRole('ROLE_CLIENTE');
            $data->getCliente()->setSucursal($data->getSucursal());
            $data->getCliente()->setCorreo($data->getEmail());
            $data->getCliente()->setRazonSocial($data->getUsername());
            $data->setEnabled(true);
            $this->userManager->updateUser($data, false);
        }

        $token = $this->tokenService->crearToken($data);

        $response = new JsonResponse([
            'user' => $data->getId(),
            'token' => $token,
        ]);

        return $response;
    }
}
