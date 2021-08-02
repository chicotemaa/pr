<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class FormularioController extends EasyAdminController
{
    protected function persistEntity($entity)
    {
        $this->setPropertyDefault($entity);

        parent::persistEntity($entity);
    }

    protected function updateEntity($entity)
    {
        $this->setPropertyDefault($entity);
        parent::updateEntity($entity);
    }

    private function setPropertyDefault($entity)
    {
        //seteo valores por defecto si esta null
        foreach ($entity->getPropiedadModulos() as $key => $propiedadModulo) {
            if (is_null($propiedadModulo->getOrden())) {
                $propiedadModulo->setOrden(1);
            }

            if (is_null($propiedadModulo->getPagina())) {
                $propiedadModulo->setPagina(1);
            }
        }
    }
}
