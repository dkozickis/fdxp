<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 16/12/15
 * Time: 21:00
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AppBundle\Entity\FlightWatch;
use AppBundle\Entity\FlightWatchInfo;

class FlightWatchReminderCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this
            ->setName('app:flightwatch:reminder')
            ->setDescription('Checks un-finalized DP points for Flight Watch');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine')->getManager();
        $flights = $em->getRepository('AppBundle:FlightWatch')->findComingUpDP();

        $fwUtils = $this->getContainer()->get('app.fw_utils');
        $fwUtils->prepareFlightsForOutput($flights);

        /** @var FlightWatch $flight */
        foreach($flights as $flight){

            $points = $flight->getInfo();

            /** @var FlightWatchInfo $point */
            foreach($points as $point){
                $output->writeln('<comment>'.$point->getPointName().'</comment>');
                $output->writeln($point->getEtoInfo());

                if($point->getCompleted() === null){
                    $output->writeln('not completed');
                }else{
                    $output->writeln('completed');
                }

                $message = \Swift_Message::newInstance()
                    ->setSubject('Hello Email')
                    ->setFrom('deniss.kozickis@lidousers.com')
                    ->setTo('johniewa@gmail.com')
                    ->setBody('testing');

                $this->getContainer()->get('mailer')->send($message);
            }

        }

    }

}
