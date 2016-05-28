<?php

namespace Mesour\ComponentsTests;

use Mesour;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

class ApplicationTest extends Mesour\Tests\BaseTestCase
{

	private $request = [
		'key' => 'val',
	];

	public function testRequest()
	{
		$application = new Mesour\UI\Application;

		$application->setRequest($this->request);

		Assert::type(Mesour\Components\Application\Request::class, $application->getRequest());
		Assert::same($application->getRequest()->get('key'), 'val');
	}

	public function testUrl()
	{
		$application = new Mesour\UI\Application;

		$url = $application->getUrl();

		Assert::type(Mesour\Components\Application\Url::class, $url);
	}

	public function testSetDefaultSession()
	{
		$application = new Mesour\UI\Application;

		$session = new Mesour\Components\Session\Session;
		$application->getContext()->setService($session, Mesour\Components\Session\ISession::class);

		$span = new Classes\Span('test_name', $application);

		Assert::same($session, $span->getSession());
	}

	public function testSetDefaultAuth()
	{
		$application = new Mesour\UI\Application;

		$auth = new Mesour\Components\Security\Permission;
		$application->getContext()->setService($auth, Mesour\Components\Security\IAuthorizator::class);

		$span = new Classes\Span('test_name', $application);

		Assert::same($auth, $span->getAuthorizator());
	}

	public function testSetDefaultLink()
	{
		$application = new Mesour\UI\Application;

		$link = new Mesour\Components\Link\Link;
		$application->getContext()->setService($link, Mesour\Components\Link\ILink::class);

		$span = new Classes\Span('test_name', $application);

		Assert::same($link, $span->getLink());
	}

	public function testSetDefaultPayload()
	{
		$application = new Mesour\UI\Application;

		$payload = new Mesour\Components\Application\Payload;
		$application->getContext()->setService($payload, Mesour\Components\Application\IPayload::class);

		$span = new Classes\Span('test_name', $application);

		Assert::same($payload, $span->getPayload());
	}

	public function testSetDefaultTranslator()
	{
		$application = new Mesour\UI\Application;

		$translator = new Mesour\ComponentsTests\Classes\TestTranslator;
		$application->getContext()->setService($translator, Mesour\Components\Localization\ITranslator::class);

		$span = new Classes\Span('test_name', $application);

		Assert::same($translator, $span->getTranslator());
	}

}

$test = new ApplicationTest();
$test->run();
