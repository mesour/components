<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class UrlTest extends \Test\BaseTestCase
{

    private $default_arguments = array(
        'key' => 'val[]'
    );

    public function testDefault()
    {
        $des = 'http://mesour.com';
        $url = new \Mesour\Components\Application\Url($des . '?key=val%5B%5D');

        Assert::same($url->getArguments(), $this->default_arguments);
        Assert::same($url->getDestination(), $des);
    }

    public function testArguments()
    {
        $des = 'http://mesour.com';
        $url = new \Mesour\Components\Application\Url($des . '?key=val%5B%5D');

        $span = new \Tests\Span('test_name');

        $created = $url->create($span, $des, array('key2' => 'val[]'));

        Assert::same($created, "http://mesour.com?m_test_name-key2=val%5B%5D&key=val%5B%5D&m_do=test_name-http%3A%2F%2Fmesour.com");
        Assert::same($url->getArguments(), $this->default_arguments);
        Assert::same($url->getDestination(), $des);
    }

}

$test = new UrlTest();
$test->run();