<?php

define('SRC_DIR', __DIR__ . '/../src/');
define("TEMP_DIR", __DIR__ . "/tmp");

require __DIR__ . "/../vendor/autoload.php";

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}
@mkdir(__DIR__ . "/log");
@mkdir(TEMP_DIR);

// configure environment
Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->enableDebugger(__DIR__ . "/log");
$configurator->setDebugMode(false);
$configurator->setTempDirectory(TEMP_DIR);
$configurator->createRobotLoader()
	->addDirectory(SRC_DIR)
	->addDirectory(__DIR__ . '/Mesour/ComponentsTests/Classes')
	->addDirectory(__DIR__ . '/Mesour/Tests')
	->register();

$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();


return $container;