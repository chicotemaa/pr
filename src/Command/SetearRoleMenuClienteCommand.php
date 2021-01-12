<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class SetearRoleMenuClienteCommand extends Command
{
    
    protected static $defaultName = 'app:setear-role-menu-cliente';
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
        ->setDescription('Setear roles menu clientes.')
        ->setHelp('Setear roles menu clientes.')
    ;
    }

    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      
        $count = 0;
        $usuarios = $this->em->getRepository(User::class)->findAll();
        if (count($usuarios) > 0) {

            foreach ($usuarios as $user) {
                $tiene=false;
                foreach ($user->getRoles() as $role) {
                    if ($role=='ROLE_ADMIN') {
                        $tiene=true;
                        break;
                    }
                    if ($role=='ROLE_CLIENTE_MENU') {
                       $tiene=true;
                    }
                }
                if (!$tiene) {
                    $user->addRole('ROLE_CLIENTE_MENU');
                    $this->em->persist($user);
                    $this->em->flush();
                }
            }
            
        }

        $output->writeln(' Rol seteado');
    }
}
