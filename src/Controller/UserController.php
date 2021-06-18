<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use FOS\UserBundle\Model\UserManagerInterface;

class UserController extends EasyAdminController
{
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            'fos_user.user_manager' => UserManagerInterface::class,
        ]);
    }

    public function createNewEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function persistEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::persistEntity($user);
    }

    public function updateEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::updateEntity($user);
    }

    /**
     * @Route("/admin/user/by/sucursal")
     */
    public function userSinLoggedUser(Request $request)
    {
        $this->request = $request;
        $term = $this->request->query->get('query');
        $session = $this->request->getSession();
        $em = $this->getDoctrine()->getManager();

        $sucursal = $this->isGranted('ROLE_ADMIN') ? null : $session->get('user_sucursal', null);

        $users = $em->getRepository(User::class)->findBySucursal($term, $sucursal);
        
        $results = [];
        foreach ($users as $key => $user) {
            $results[] = [
                'id' => $user->getId(),
                'text' => $user->__toString(),
            ];
        }

        return new JsonResponse([
            'results' => $results,
        ]);
    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection,
            $sortField, $dqlFilter);
        //$queryBuilder = $this->armarQuery($queryBuilder);
        return $queryBuilder;
    }

    //lista alumnos inscriptos a una oferta local seleccionada
    public function armarQuery($queryBuilder)
    {
        /* aca tengo que obtener el usuario logueado
        dump($this->getUser()); die;
        if ($this->getUser()) {
            $queryBuilder
                ->join('entity.cliente', 'c')
                ->andwhere('c = :val')
                ->setParameter('val', $oferta)
                ;
        }*/
        $idCliente = $this->getUser()->getId();
        //dump($idCliente); die;
        if ($idCliente !='null' && !empty($idCliente)) {
            $queryBuilder
            ->join('entity.cliente', 'c')
            ->andWhere('c.id = :idCliente')
            ->setParameter('idCliente', $idCliente);
        }
        return $queryBuilder;

    }

}
