<?php

namespace CodeMeme\Bundle\CodeMemeDaemonBundle;

/**
 * Daemon is a php5 wrapper class for the PEAR library System_Daemon
 *
 * PHP version 5
 *
 * @category  CodeMeme
 * @package   CodeMemeDaemonBundle
 * @author    Jesse Greathouse <jesse.greathouse@gmail.com>
 * @copyright 2011 CodeMeme (https://github.com/organizations/CodeMeme)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @link      https://github.com/CodeMeme/CodeMemeDaemonBundle
 */

use CodeMeme\Bundle\CodeMemeDaemonBundle\System\Daemon as System_Daemon;

class Daemon
{

    private $_config = array();
    private $_pid;
    private $_interval = 2;
    
    public function __construct($options) 
    {
        if (!empty($options))
        {
            $this->setConfig($options);
        }   
    }
    
    public function setConfig($config)
    {
        $this->_config = $config;
    }
    
    public function getPid()
    {
        if (file_exists($this->_config['appPidLocation'])) {
            $fh = fopen($this->_config['appPidLocation'], "r");
            $pid = fread($fh, filesize($this->_config['appPidLocation']));
            fclose($fh);
            return trim($pid);
        } else {
            return null;
        }
        
    }
    
    public function setPid($pid)
    {
        $this->_pid = $pid;
    }
    
    public function setInterval($interval)
    {
        $this->_interval = $interval;
    }
    
    public function getInterval()
    {
        return $this->_interval;
    }
    
    public function getConfig()
    {
        return $this->_config;
    }
    
    public function start()
    {
        System_Daemon::setOptions($this->getConfig());
        System_Daemon::start();
        
        System_Daemon::info('{appName} System Daemon Started at %s',
            date("F j, Y, g:i a")
        );
        
        $this->setPid($this->getPid()); 
        
    }
    
    public function iterate($sec) {
        System_Daemon::iterate($sec);
    }
    
    public function isRunning() 
    {
        if (!System_Daemon::isDying() && $this->_pid != null && $this->_pid == $this->getPid()) {
            System_Daemon::iterate($this->_interval);
            return true;
        } else {
            return false;
        }
    }
    
    public function stop()
    {
        if (file_exists($this->_config['appPidLocation'])) {
            unlink($this->_config['appPidLocation']);
            System_Daemon::info('{appName} System Daemon Terminated at %s',
                date("F j, Y, g:i a")
            );
        } else {
            System_Daemon::info('{appName} System Daemon is not running. Could not terminate at %s',
                date("F j, Y, g:i a")
            );
        }
    }
}
