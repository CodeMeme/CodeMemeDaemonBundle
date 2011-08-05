<?php

namespace CodeMeme\Bundle\CodeMemeDaemonBundle\Service;

class ExampleControl
{  

   private $logger;
    
   public function __construct($logger = null)
   {
       $this->logger = $logger;
   }

   
   public function run()
   {
       $this->logger->info('Entered new cycle at: ' . get_class($this). '::run()');
       $this->logger->info('EXECUTING METHOD: ' . get_class($this). '::test()');
       $this->test('Aliens will meet you at area 51!');
   }
   
   public function test($value = null)
   {
       if ($value !== null) {
           $this->logger->info($value);
        }
   }
}