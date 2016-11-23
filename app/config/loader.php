<?php
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
    		$config->application->libraryDir,
    		$config->application->formsDir
    ]
)->register();
$loader->registerClasses([
	'SecurityPlugin' => APP_PATH . '/plugins/SecurityPlugin.php'
]);

// $loader->registerNamespaces([
// 	'ULogin\Auth' => APP_PATH . '/library/ULogin/Auth.php'

// ]);


// $loader->registerNamespaces([
//         'ULogin\Auth' => APP_PATH . '/libraries/ULogin/'
//     ]);