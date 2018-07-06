<?php

namespace AppBundle\Command;

use AppBundle\AppBundle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TjkCrawlCommand extends ContainerAwareCommand
{
    protected function configure(){

        $this
            ->setName('crawl:tjk')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

        $class = '\\AppBundle\\Crawlers\\RaceSchedule';
        $crawler = new $class($this->getContainer()->get("kernel"));
        $crawler->crawl();

        $output->writeln('Command result.');
    }

}
