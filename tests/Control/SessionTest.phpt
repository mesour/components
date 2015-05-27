<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class SessionTest extends \Test\BaseTestCase
{

    public function testSetAndGetSections()
    {
        $session = new \Mesour\Components\Session\Session;

        $test = $session->getSection('test');

        Assert::true($session->hasSection('test'));
        Assert::type('Mesour\Components\Session\ISessionSection', $test);
    }

    public function testSetAndGetValue()
    {
        $session = new \Mesour\Components\Session\Session;
        $test = $session->getSection('test');

        $testValue = 'test_text';

        $test->set('test', $testValue);

        Assert::same($test->get('test'), $testValue);
        Assert::same($test->get('unknown_key', 'default'), 'default');
    }

    public function testExceptions()
    {
        Assert::exception(function() {
            $session = new \Mesour\Components\Session\Session;
            $session->getSection(array());
        }, 'Mesour\Components\InvalidArgumentException');

        Assert::exception(function() {
            $session = new \Mesour\Components\Session\Session;
            $test = $session->getSection('test');

            $test->set(array(), TRUE);
        }, 'Mesour\Components\InvalidArgumentException');
    }

}

$test = new SessionTest();
$test->run();