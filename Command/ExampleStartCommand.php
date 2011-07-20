<?php

namespace CodeMeme\Bundle\CodeMemeDaemonBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ExampleStartCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {   
        $this->setName('example:start')
             ->setDescription('Starts the example daemon')
             ->setHelp(<<<EOT
The <info>{$this->getName()}</info> Run the example daemon in the background.
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    
        $this->container->get('example.daemon');
        $daemon->start();
        
        while ($daemon->isRunning()) {
            $this->container->get('example.control')->run();
        }
        
        $daemon->stop();
    }

}
