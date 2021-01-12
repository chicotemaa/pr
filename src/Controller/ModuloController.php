<?php

namespace App\Controller;

use App\Entity\Modulo;
use App\Form\ModuloDependenciasType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class ModuloController extends EasyAdminController
{
    protected function persistEntity($entity)
    {
        //seteo valores por defecto si esta null
        foreach ($entity->getPropiedadItems() as $key => $propiedadItem) {
            if (is_null($propiedadItem->getOrden())) {
                $propiedadItem->setOrden(1);
            }

            if (is_null($propiedadItem->getAncho())) {
                $propiedadItem->setAncho(12);
            }
        }

        parent::persistEntity($entity);
    }

    protected function updateEntity($entity)
    {
        //seteo valores por defecto si esta null
        foreach ($entity->getPropiedadItems() as $key => $propiedadItem) {
            if (is_null($propiedadItem->getOrden())) {
                $propiedadItem->setOrden(1);
            }

            if (is_null($propiedadItem->getAncho())) {
                $propiedadItem->setAncho(12);
            }
        }

        //actualizo formularios que contengan el modulo
        foreach ($entity->getPropiedadModulos() as $key2 => $pModulos) {
            $pModulos->getFormulario()->setUpdatedAt(new \DateTime());
        }

        parent::updateEntity($entity);
    }

    public function agregarDependenciasAction()
    {
        $entity = $this->em->getRepository(Modulo::class)->find($this->request->get('id'));

        $form = $this->createForm(ModuloDependenciasType::class, $entity);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            parent::updateEntity($entity);

            return $this->redirectToReferrer();
        }

        return $this->render('Modulo/dependencias.html.twig', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
    }
}
