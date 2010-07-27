<?php

/**
 * My Application bootstrap file.
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */

set_time_limit(0);

use Nette\Debug;
use Nette\Environment;
use Nette\Application\Route;
use Nette\Application\SimpleRouter;



// Step 1: Load Nette Framework
// this allows load Nette Framework classes automatically so that
// you don't have to litter your code with 'require' statements
require LIBS_DIR . '/Nette/loader.php';



// Step 2: Configure environment
// 2a) enable Nette\Debug for better exception and error visualisation
Debug::enable();

// 2b) load configuration from config.ini file
Environment::loadConfig();
class EM
{
	public static function factory()
	{
		$config = new \Doctrine\ORM\Configuration();
		$cache = new \Nella\NetteDoctrineCache();
		$config->setMetadataCacheImpl($cache);
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(APP_DIR . "/models"));
		$config->setQueryCacheImpl($cache);
		$config->setProxyDir(APP_DIR . '/models/Proxies');
		$config->setProxyNamespace('App\Models\Proxies');
		$config->setAutoGenerateProxyClasses(TRUE);
		Debug::$counters['queries'] = 0;

		return \Doctrine\ORM\EntityManager::create((array) Environment::getConfig('database'), $config);
	}
}


// Step 3: Configure application
// 3a) get and setup a front controller
$application = Environment::getApplication();
$application->errorPresenter = 'Error';
//$application->catchExceptions = TRUE;



// Step 4: Setup application router
$router = $application->getRouter();

$router[] = new Route('index.php', array(
	'presenter' => 'Homepage',
	'action' => 'default',
), Route::ONE_WAY);

$router[] = new Route('<action>', array(
	'presenter' => 'Homepage',
	'action' => 'default',
));



// Step 5: Run the application!
$application->run();
