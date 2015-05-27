<?php

use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ApplicationTest extends \Test\BaseTestCase
{

    private $request = array(
        'key' => 'val'
    );

    public function testRequest()
    {
        $application = new \Mesour\UI\Application;

        $application->setRequest($this->request);

        Assert::type('Mesour\Components\Application\Request', $application->getRequest());
        Assert::same($application->getRequest()->get('key'), 'val');
    }

    public function testUrl()
    {
        $application = new \Mesour\UI\Application;

        $url = $application->getUrl();

        Assert::type('Mesour\Components\Application\Url', $url);
    }

    public function testSetDefaultSession()
    {
        $application = new \Mesour\UI\Application;

        $session = new \Mesour\Components\Session\Session;
        $application->setSession($session);

        $span = new \Tests\Span('test_name', $application);

        Assert::same($span->getSession(), $session);
    }

    public function testSetDefaultAuth()
    {
        $application = new \Mesour\UI\Application;

        $auth = new \Mesour\Components\Security\Auth;
        $application->setAuth($auth);

        $span = new \Tests\Span('test_name', $application);

        Assert::same($span->getAuth(), $auth);
    }

    public function testSetDefaultLink()
    {
        $application = new \Mesour\UI\Application;

        $link = new \Mesour\Components\Link\Link;
        $application->setLink($link);

        $span = new \Tests\Span('test_name', $application);

        Assert::same($span->getLink(), $link);
    }

    public function testSetDefaultPayload()
    {
        $application = new \Mesour\UI\Application;

        $payload = new \Mesour\Components\Application\Payload;
        $application->setPayload($payload);

        $span = new \Tests\Span('test_name', $application);

        Assert::same($span->getPayload(), $payload);
    }

    public function testSetDefaultTranslator()
    {
        $application = new \Mesour\UI\Application;

        $translator = new \Mesour\Components\Localize\Translator;
        $application->setTranslator($translator);

        $span = new \Tests\Span('test_name', $application);

        Assert::same($span->getTranslator(), $translator);
    }

}

$test = new ApplicationTest();
$test->run();