<?php

namespace CodeMeme\Bundle\CodeMemeDaemonBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use CodeMeme\Bundle\CodeMemeDaemonBundle\Daemon;

class ExampleStopCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {   
        $this->setName('example:stop')
             ->setDescription('Stops the example daemon')
             ->setHelp(<<<EOT
The <info>{$this->getName()}</info> Stop the Example daemon from running in the background.
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $daemon = new Daemon($this->getContainer()->getParameter('example.daemon.options'));
        $daemon->stop();
    }

}