<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class HandlesTest extends \Test\BaseTestCase
{

    static public $testArr = array(
        'key' => 'val[]'
    );

    private $request = array(
        'm_do' => '-test-change',
        'm_-test-page' => '2',
    );

    private $requestArr = array(
        'm_do' => '-test-changeArray',
        'm_-test-page' => NULL,
    );

    private $requestEmpty = array(
        'm_do' => '-test-change',
    );

    public function __construct()
    {
        $this->requestArr['m_-test-page'] = self::$testArr;
    }

    public function testCallHandler()
    {
        $application = new \Mesour\UI\Application;

        $application->setRequest($this->request);

        $span = new \Tests\Span('test', $application);

        $span->beforeRender();

        $span->assertHandleCalled();
    }

    public function testCallHandlerRequiredException()
    {
        Assert::exception(function () {
            $application = new \Mesour\UI\Application;

            $application->setRequest($this->requestEmpty);

            $span = new \Tests\Span('test', $application);

            $span->beforeRender();

            $span->assertHandleCalled();
        }, 'Mesour\Components\BadStateException');
    }

    public function testArrayValue()
    {
        $application = new \Mesour\UI\Application;

        $application->setRequest($this->requestArr);

        $span = new \Tests\Span('test', $application);

        $span->beforeRender();

        $span->assertHandleCalled();
    }

    public function testArrayValueException()
    {
        Assert::exception(function () {
            $application = new \Mesour\UI\Application;

            $req = $this->request;
            $req['m_do'] = '-test-changeArray';

            $application->setRequest($req);

            $span = new \Tests\Span('test', $application);

            $span->beforeRender();

            $span->assertHandleCalled();
        }, 'Mesour\Components\BadStateException');
    }

    public function testArrayValueRequiredException()
    {
        Assert::exception(function () {
            $application = new \Mesour\UI\Application;

            $req = $this->request;
            $req['m_do'] = '-test-changeArrayRequired';

            $application->setRequest($req);

            $span = new \Tests\Span('test', $application);

            $span->beforeRender();

            $span->assertHandleCalled();
        }, 'Mesour\Components\BadStateException');
    }

    public function testNotExistHandlerException()
    {
        Assert::exception(function () {
            $application = new \Mesour\UI\Application;

            $req = $this->request;
            $req['m_do'] = '-test-not_exists';

            $application->setRequest($req);

            $span = new \Tests\Span('test', $application);

            $span->beforeRender();

            $span->assertHandleCalled();
        }, 'Mesour\Components\BadStateException');
    }

}

$test = new HandlesTest();
$test->run();