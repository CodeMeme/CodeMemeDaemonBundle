#CodeMemeDaemonBundle ![project status](http://stillmaintained.com/CodeMeme/CodeMemeDaemonBundle.png) #
##Overview##
CodeMemeDaemonBundle is a wrapper for the PEAR library System_Daemon which was created by Kevin Vanzonneveld.

This will enable you to install the symfony bundle and easily convert your Symfony2 console scripts into system daemons.

pcntl is required to be configured in your PHP binary to use this. On my Ubuntu server I was able to install pcntl easily with the following command:

    sudo apt-get install php-5.3-pcntl-zend-server 

##System_Daemon PEAR package##
System_Daemon is a PHP class that allows developers to create their own daemon 
applications on Linux systems. The class is focussed entirely on creating & 
spawning standalone daemons

More info at:

- [Blog Article: Create daemons in PHP][1]
- [Report Issues][2]
- [Package Statistics][3]
- [Package Home][4]

  [1]: http://kevin.vanzonneveld.net/techblog/article/create_daemons_in_php/
  [2]: http://pear.php.net/bugs/report.php?package=System_Daemon
  [3]: http://pear.php.net/package-stats.php?pid=798&cid=37
  [4]: http://pear.php.net/package/System_Daemon


##DaemonBundle Config##
Place CodeMeme\Daemonbundle in your src directory and do the following:

### Deps ###
add the bundle to your deps configuration

    [CodeMemeDaemonBundle]
        git=http://github.com/CodeMeme/CodeMemeDaemonBundle.git
        target=/bundles/CodeMeme/Bundle/CodeMemeDaemonBundle

### autoload.php ###
Add the following to your autoload.php file:

    $loader->registerNamespaces(array(
        //...
        'CodeMeme'     => __DIR__.'/../vendor/bundles',
    ));

### appKernel.php ###
Add The CodeMemeDaemonBundle to your kernel bootstrap sequence

    public function registerBundles()
    {
        $bundles = array(
            //...
            new CodeMeme\Bundle\CodeMemeDaemonBundle\CodeMemeDaemonBundle(),
        );
        //...

        return $bundles;
    }

### config.yml ###
By Default, system daemons have a sensible configuration. If you need to change any configuration setting , you could do it by adding this configuration to your project config. Only the values that need to be changed should be added, the bundle extension will merge your daemon configs into its defaults.

    app/config.yml

    #CodeMemeDaemonBundle Configuration Example
    code_meme_daemon:
        daemons:
            #creates a daemon using default options
            example: ~

            #an example of all the available options
            explicitexample:
                appName: example
                appDir: %kernel.root_dir%
                appDescription: Example of how to configure the DaemonBundle
                logLocation: %kernel.logs_dir%/%kernel.environment%.example.log
                authorName: Jesse Greathouse
                authorEmail: jesse.greathouse@gmail.com
                appPidLocation: %kernel.cache_dir%/example/example.pid
                sysMaxExecutionTime: 0
                sysMaxInputTime: 0
                sysMemoryLimit: 1024M
                appUser: apache
                appGroup: apache
                appRunAsGID: 1000
                appRunAsUID: 1000

#### RunAs ####
You can run the daemon as a different user or group depending on what is best for your application. By default it will resolve the user and group of the user who is running the daemon from the command console, but if you want to run as a different user you can use the appUser, appGroup or appRunAsGID, appRunAsUID options. Remember if you need to run as a different user you must start the daemon as sudo or a superuser.

To find out the group and user id of a specific user you can use the following commands.

    jesse@picard:~/ninethousand.org$ id -u www-data
    jesse@picard:~/ninethousand.org$ id -g www-data

##Creating a Daemon##
The Following links are examples of how to use a system daemon in an example project

- [Start Command][8]
- [Stop Command][9]
- [Restart Command][10]
- [Example Service Class][5]
- [Config of Control Service][6]

  [5]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Service/ExampleControl.php
  [6]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Resources/config/daemon.xml
  [8]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Command/ExampleStartCommand.php
  [9]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Command/ExampleStopCommand.php
  [10]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Command/ExampleRestartCommand.php
  
##Usage##
Once you have Daemonized your symfony Console Commands, you can simply run them from the command line like so:

    jesse@picard:~/codememe$ php app/console jobqueue:start

    jesse@picard:~/codememe$ php app/console jobqueue:stop

    jesse@picard:~/codememe$ php app/console jobqueue:restart
