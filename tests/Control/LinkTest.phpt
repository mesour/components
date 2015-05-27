<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class LinkTest extends \Test\BaseTestCase
{

    public function testCreate()
    {
        $link = new \Mesour\Components\Link\Link;

        $created = $link->create('http://mesour.com');

        Assert::type('Mesour\Components\Link\IUrl', $created);
    }

    public function testCreateWithArguments()
    {
        $link = new \Mesour\Components\Link\Link;

        $created = $link->create('http://mesour.com', array('key' => 'val'));

        Assert::count(1, $created->getArguments());
    }

    public function testBadDestinationException()
    {
        Assert::exception(function () {
            $link = new \Mesour\Components\Link\Link;

            $link->create(array());
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new LinkTest();
$test->run();