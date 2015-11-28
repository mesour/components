<?php

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir."/".$object))
                    rrmdir($dir."/".$object);
                else
                    @unlink($dir."/".$object);
            }
        }
        @rmdir($dir);
    }
}

define('SRC_DIR', __DIR__ . '/../src/');
define("TEMP_DIR", __DIR__ . "/tmp");

require __DIR__ . "/../vendor/autoload.php";

if (!class_exists('Tester\Assert')) {
    echo "Install Nette Tester using `composer update --dev`\n";
    exit(1);
}
@mkdir(__DIR__ . "/log");
rrmdir(TEMP_DIR);
@mkdir(TEMP_DIR);

Tester\Helpers::purge(TEMP_DIR);

$configurator = new Nette\Configurator;
$configurator->enableDebugger(__DIR__ . "/log");
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(TEMP_DIR);
$configurator->createRobotLoader()
    ->addDirectory(SRC_DIR)
    ->addDirectory(__DIR__ . '/classes')
    ->register();

$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();

Tester\Environment::setup();

return $container;