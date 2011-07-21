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
    private $defaultUser = null;

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
    
    private function getDefaultConfig($name, $container)
    {
        if (null === $this->defaultUser && function_exists('posix_geteuid')) {
                $this->defaultUser = posix_geteuid();
        }   
        
        $defaults = array(
            'appName'               => $name,
            'appDir'                => $container->getParameter('kernel.root_dir'),
            'appDescription'        => 'CodeMeme System Daemon',
            'logLocation'           => $container->getParameter('kernel.logs_dir') . '/' . $container->getParameter('kernel.environment'). '.' . $name . '.daemon.log',
            'authorName'            => 'CodeMeme',
            'authorEmail'           => 'symfony2.kernel@127.0.0.1',
            'appPidLocation'        => $container->getParameter('kernel.cache_dir') . '/'. $name . '/' . $name . '.daemon.pid',
            'sysMaxExecutionTime'   => 0,
            'sysMaxInputTime'       => 0,
            'sysMemoryLimit'        => '1024M',
            'appRunAsUID'           => $this->defaultUser
            );
            
            return $defaults;
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
            
            if (isset($cnf['appUser']) || isset($cnf['appGroup'])) {
                if (isset($cnf['appUser']) && (function_exists('posix_getpwnam'))) {
                    $user  = posix_getpwnam($cnf['appUser']);
                    if ($user) {
                        $cnf['appRunAsUID'] = $user['uid'];
                    }
                }
                
                if (isset($cnf['appGroup']) && (function_exists('posix_getgrnam'))) {
                    $group = posix_getgrnam($cnf['appGroup']);
                    if ($group) {
                        $cnf['appRunAsGID'] = $group['gid'];
                    }
                }
                
                if (!isset($cnf['appRunAsGID'])) {
                    $user = posix_getpwuid($cnf['appRunAsUID']);
                    $cnf['appRunAsGID'] = $user['gid'];
                }
            }
            
            $container->setParameter($name.'.daemon.options', 
                                     array_merge($this->getDefaultConfig($name, $container), $cnf));
        }
        
    }
    
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }
}
