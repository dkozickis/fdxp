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

class WaypointImportCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:waypoints:import')
            ->setDescription('Imports Waypoints from specified file')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Specify Waypoint file'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        set_time_limit(300);

        $em = $this->getContainer()->get('doctrine')->getManager();

        $crawler = new Crawler();
        $crawler->addXmlContent(file_get_contents($this->getContainer()->get('kernel')->getRootDir().'/../'.$file));

        $filter = $crawler->filterXPath("//codeType[contains(text(), 'ICAO')]/..");
        $progress = new ProgressBar($output, ceil(count($filter) / 20));

        $progress->start();
        $batchSize = 20;

        if (iterator_count($filter) > 1) {

            foreach ($filter as $i => $content) {
                $crawler = new Crawler($content);
                if ($crawler->filterXPath('//codeType')->text() == 'ICAO') {

                    $wpt_db = new Waypoints();

                    $lat = $crawler->filterXPath('//geoLat')->text();
                    $lon = $crawler->filterXPath('//geoLong')->text();

                    $lat = (substr($lat, -1) == 'N') ? rtrim($lat, "N") : "-".rtrim($lat, "S");
                    $lon = (substr($lon, -1) == 'E') ? ltrim(rtrim($lon, "E"), '0') : "-".ltrim(rtrim($lon, "W"), 0);

                    $wpt_db->setWptId($crawler->filterXPath('//codeId')->text())
                        ->setLat((float) $lat)
                        ->setLon((float) $lon);

                    $em->persist($wpt_db);

                    if (($i % $batchSize) === 0) {
                        $em->flush();
                        $em->clear(); // Detaches all objects from Doctrine!
                        $progress->advance();
                    }

                }

            }
            $em->flush();
            $em->clear();
            $progress->finish();
            $output->writeln('<comment>Completed</comment>');

        }
    }
}