<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class RequestTest extends \Test\BaseTestCase
{

    private $request = array(
        'key' => 'val'
    );

    public function testGetValue()
    {
        $request = new \Mesour\Components\Application\Request($this->request);

        Assert::same($request->get('key'), 'val');
        Assert::same($request->get('unknown_key', 'default'), 'default');
    }

}

$test = new RequestTest();
$test->run();