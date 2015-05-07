<?php

namespace AppBundle\Command;

use AppBundle\Entity\Waypoints;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class WaypointClearCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:waypoints:clear')
            ->setDescription('Clears Waypoints table');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getConnection();

        $em->query('TRUNCATE TABLE waypoints')->execute();

        $output->writeln('<comment>Completed</comment>');
    }
}