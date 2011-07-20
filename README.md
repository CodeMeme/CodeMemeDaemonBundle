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
add the bundle and jobqueue component to your deps configuration

    [CodeMemeDaemonBundle]
        git=http://github.com/CodeMeme/CodeMemeDaemonBundle.git
        target=/bundles/CodeMeme/Bundle/CodeMemeDaemonBundle

### autoload.php ###
Add the following to your autoload.php file:

    $loader->registerNamespaces(array(
        //...
        'CodeMeme'     => array(__DIR__.'/../vendor/bundles'),
    ));

### appKernel.php ###
Add The Jobqueue bundle to your kernel bootstrap sequence

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
                appRunAsGID: 1
                appRunAsUID: 1
    

##Creating a Daemon##
The Following links are examples of how to use a system daemon in an example project

- [Start Command][8]
- [Stop Command][9]
- [Restart Command][10]
- [Example Service Class][5]
- [Config of Control Service][6]
- [Control Service DiC][7]

  [5]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Service/ExampleControl.php
  [6]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Resources/config/services.xml
  [7]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/DependencyInjection/ExampleExtension.php
  [8]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Command/StartCommand.php
  [9]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Command/StopCommand.php
  [10]: https://github.com/CodeMeme/CodeMemeDaemonBundle/blob/master/Command/RestartCommand.php
