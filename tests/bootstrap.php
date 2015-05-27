<?php

define('SRC_DIR', __DIR__ . '/../src/');
define('BRIDGES_DIR', __DIR__ . '/../bridges/');
define("TEMP_DIR", __DIR__ . "/tmp/");

require_once __DIR__ . '/../vendor/autoload.php';

@mkdir(__DIR__ . "/log", 0777);
@mkdir(__DIR__ . "/tmp", 0777);

$loader = new Nette\Loaders\RobotLoader;
$loader->addDirectory(SRC_DIR)
    ->addDirectory(__DIR__ . '/classes')
    ->addDirectory(BRIDGES_DIR)
    ->setCacheStorage(new Nette\Caching\Storages\FileStorage(TEMP_DIR))
    ->register();

if (!class_exists('Tester\Assert')) {
    echo "Install Nette Tester using `composer update --dev`\n";
    exit(1);
}

Tester\Helpers::purge(TEMP_DIR);
Tester\Environment::setup();

function id($val) {
    return $val;
}