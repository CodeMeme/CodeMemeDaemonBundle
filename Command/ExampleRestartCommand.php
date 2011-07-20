<?php

namespace CodeMeme\Bundle\CodeMemeDaemonBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ExampleRestartCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {   
        $this->setName('example:restart')
             ->setDescription('Starts the example daemon')
             ->setHelp(<<<EOT
The <info>{$this->getName()}</info>Restarts the example daemon in the background.
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $daemon = $this->container->get('example.daemon');
        $daemon->stop();
        $daemon->iterate(5);
        $daemon->start();
        
        while ($daemon->isRunning()) {
            $this->container->get('example.control')->run();
        }
        
        $daemon->stop();
    }

}
