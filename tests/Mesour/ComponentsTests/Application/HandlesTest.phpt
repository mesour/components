<?php

namespace Mesour\ComponentsTests;

use Tester\Assert;
use Mesour;
use Mesour\ComponentsTests\Classes;

require_once __DIR__ . '/../../../bootstrap.php';

class HandlesTest extends Mesour\Tests\BaseTestCase
{

	static public $testArr = [
		'key' => 'val[]',
	];

	private $request = [
		'm_do' => '-test-change',
		'm_-test-page' => '2',
	];

	private $requestArr = [
		'm_do' => '-test-changeArray',
		'm_-test-page' => null,
	];

	private $requestEmpty = [
		'm_do' => '-test-change',
	];

	public function __construct()
	{
		$this->requestArr['m_-test-page'] = self::$testArr;
	}

	public function testCallHandler()
	{
		$application = new Mesour\UI\Application;

		$application->setRequest($this->request);

		$span = new Classes\Span('test', $application);

		$span->beforeRender();

		$span->assertHandleCalled();
	}

	public function testCallHandlerRequiredException()
	{
		Assert::exception(function () {
			$application = new Mesour\UI\Application;

			$application->setRequest($this->requestEmpty);

			$span = new Classes\Span('test', $application);

			$span->beforeRender();

			$span->assertHandleCalled();
		}, Mesour\InvalidArgumentException::class);
	}

	public function testArrayValue()
	{
		$application = new Mesour\UI\Application;

		$application->setRequest($this->requestArr);

		$span = new Classes\Span('test', $application);

		$span->beforeRender();

		$span->assertHandleCalled();
	}

	public function testArrayValueException()
	{
		Assert::exception(function () {
			$application = new Mesour\UI\Application;

			$req = $this->request;
			$req['m_do'] = '-test-changeArray';

			$application->setRequest($req);

			$span = new Classes\Span('test', $application);

			$span->beforeRender();

			$span->assertHandleCalled();
		}, Mesour\UnexpectedValueException::class);
	}

	public function testArrayValueRequiredException()
	{
		Assert::exception(function () {
			$application = new Mesour\UI\Application;

			$req = $this->request;
			$req['m_do'] = '-test-changeArrayRequired';

			$application->setRequest($req);

			$span = new Classes\Span('test', $application);

			$span->beforeRender();

			$span->assertHandleCalled();
		}, Mesour\UnexpectedValueException::class);
	}

	public function testNotExistHandlerException()
	{
		Assert::exception(function () {
			$application = new Mesour\UI\Application;

			$req = $this->request;
			$req['m_do'] = '-test-not_exists';

			$application->setRequest($req);

			$span = new Classes\Span('test', $application);

			$span->beforeRender();

			$span->assertHandleCalled();
		}, Mesour\Components\BadRequestException::class);
	}

}

$test = new HandlesTest();
$test->run();