<?php

namespace CodeMeme\Bundle\CodeMemeDaemonBundle\System\Daemon\OS;

/* vim: set noai expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
/**
 * System_Daemon turns PHP-CLI scripts into daemons.
 * 
 * PHP version 5
 *
 * @category  CodeMeme
 * @package   CodeMemeDaemonBundle
 * @author    Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id$
 * @link      http://trac.plutonia.nl/projects/system_daemon
 */

/**
 * A System_Daemon_OS driver for Windows
 *
 * @category  System
 * @package   Daemon
 * @author    Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id$
 * @link      http://trac.plutonia.nl/projects/system_daemon
 * * 
 */

use CodeMeme\Bundle\CodeMemeDaemonBundle\System\Daemon\OS;

class Windows extends OS
{
    /**
     * Determines wether this system is compatible with this OS
     *
     * @return boolean
     */
    public function isInstalled() 
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== "WIN") {
            return false;
        }
        
        return true;
    }
}
