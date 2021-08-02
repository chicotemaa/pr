<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\MailReader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Solicitud;

class ReadMailsCommand extends Command
{
    protected static $defaultName = 'app:read-mails';
    private $mailReader;
    private $container;

    public function __construct(MailReader $mailReader, ContainerInterface $container)
    {
        $this->mailReader = $mailReader;
        parent::__construct();
        $this->container = $container;
    }

    protected function configure()
    {
        $this
        ->setDescription('Read the emails of the configured account.')
        ->setHelp('This command allows you to read and parse the emails of the configured account.')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
        'Reading Mails',
        '============',
        '',
    ]);

        $count = 0;
        $solicitudes = $this->mailReader->read();
        if (count($solicitudes) > 0) {
            $em = $this->container->get('doctrine')->getManager();
            foreach ($solicitudes as $solicitud) {
                $existente = $em->getRepository(Solicitud::class)
                    ->findOneByNroIncidencia($solicitud->getNroIncidencia());
                if (null == $existente) {
                    $em->persist($solicitud);
                    $em->flush();
                    ++$count;
                }
            }
        }

        $output->writeln($count.' Solicitudes created');
    }
}
