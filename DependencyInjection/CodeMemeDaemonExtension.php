<?php

namespace CodeMeme\Bundle\CodeMemeDaemonBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use CodeMeme\Bundle\CodeMemeDaemonBundle\CodeMemeDaemonBundleException;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CodeMemeDaemonExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        //$config = $processor->processConfiguration($configuration, $configs);
        
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('daemon.xml');
        
        $config = $this->mergeExternalConfig($configs);
        $this->_init($config, $container);
    }
    
    private function mergeExternalConfig($config)
    {
        $mergedConfig = array();

        foreach ($config as $cnf)
        {
            $mergedConfig = array_merge($mergedConfig, $cnf);
        }
        
        return $mergedConfig;
    }
    
    private function getDefaultConfig($name)
    {
        return array(
            'appName'               => $name,
            'appDir'                => '%kernel.root_dir',
            'appDescription'        => 'CodeMeme System Daemon',
            'logLocation'           => '%kernel.logs_dir%/%kernel.environment%.' . $name . '.daemon.log',
            'authorName'            => 'CodeMeme',
            'authorEmail'           => 'symfony2.kernel@127.0.0.1',
            'appPidLocation'        => '%kernel.cache_dir%/'. $name . '/' . $name . '.daemon.pid',
            'sysMaxExecutionTime'   => 0,
            'sysMaxInputTime'       => 0,
            'sysMemoryLimit'        => '1024M',
            'appRunAsGID'           => 1,
            'appRunAsUID'           => 1);
    }
    
    private function _init($config, $container)
    {
        //merges each configured daemon with default configs 
        //and makes sure the pid directory is writable
        $cacheDir = $container->getParameter('kernel.cache_dir'); 
        $filesystem = $container->get('codememe.daemon.filesystem');
        foreach ($config['daemons'] as $name => $cnf)
        {
            if (NULL == $cnf) $cnf = array();
            try {
                $filesystem->mkdir($cacheDir . '/'. $name . '/', 0777);
            } catch (CodeMemeDaemonBundleException $e) {
                echo 'CodeMemeDaemonBundle exception: ',  $e->getMessage(), "\n";
            }
            
            $container->setParameter($name.'.daemon.options', 
                                     array_merge($this->getDefaultConfig($name), $cnf));
                                     
            $container->setDefinition($name.'.daemon', new Definition(
                $container->getParameter('codememe.daemon.class'),
                $container->getParameter($name.'.daemon.options')));
        }
        
    }
    
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }
}
