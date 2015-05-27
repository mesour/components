<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class PayloadTest extends \Test\BaseTestCase
{

    public function testSetAndGetValue()
    {
        $payload = new \Mesour\Components\Application\Payload;

        $testValue = 'test_text';

        $payload->set('test', $testValue);
        Assert::same($payload->get('test'), $testValue);
        Assert::same($payload->get('unknown_key', 'default'), 'default');

        $payload->test2 = $testValue;
        Assert::same($payload->test2, $testValue);
        Assert::true(isset($payload->test2));

        unset($payload->test2);
        Assert::null($payload->test2);
        Assert::false(isset($payload->test2));
    }

    public function testExceptions()
    {
        Assert::exception(function () {
            $session = new \Mesour\Components\Session\Session;
            $session->getSection(array());
        }, 'Mesour\Components\InvalidArgumentException');

        Assert::exception(function () {
            $payload = new \Mesour\Components\Application\Payload;

            $payload->set(array(), TRUE);
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new PayloadTest();
$test->run();