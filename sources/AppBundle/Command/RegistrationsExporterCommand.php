<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegistrationsExporterCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('export-registrations')
            ->addArgument('file', InputArgument::REQUIRED)
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        if (null === ($event = $container->get('app.event_repository')->getNextEvent())) {
            $output->writeln('No event found');
            return;
        }

        $file = new \SplFileObject($input->getArgument('file'), 'w+');

        $container->get('app.registration_export_generator')->export($event, $file);
    }
}
