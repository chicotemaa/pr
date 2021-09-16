<?php

namespace App\Action;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class UserList
{
    private $security;

    public function __construct(EntityManagerInterface $em,Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function __invoke(Request $request)
    {
        $term = null ;
        $roles = $this->security->getUser()->getRoles();

        $sucursal = in_array('ROLE_ADMIN', $roles)? null : $this->security->getUser()->getSucursal()->getId();
        
        
        $users = $this->em->getRepository(User::class)->findBySucursal($term, $sucursal);
        
        $results = [];
        foreach ($users as $key => $user) {
            $results[] = [
                'id' => $user->getId(),
                'nombre' => $user->__toString(),
            ];
        }

        return new JsonResponse([
            'results' => $results,
        ]);
    }
}
