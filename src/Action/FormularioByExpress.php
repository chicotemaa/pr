<?php

namespace App\Action;

use App\Entity\Formulario;
use App\Entity\FormularioResultadoExpress;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FormularioByExpress
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function __invoke(Request $request)
    {
        $estados = $request->query->get('estado', null);

        if ($estados) {
            $formularios = $this->em->getRepository(FormularioResultadoExpress::class)->findByUserEstado($this->security->getUser()->getId(), $estados);
        } else {
            $formularios = $this->em->getRepository(Formulario::class)->findByExpress();
        }

        return $formularios;
    }
}
