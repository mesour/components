<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour\Components\Application\Configuration;
use Mesour\Components\Application\Context;
use Mesour\Components\Application\IApplication;
use Mesour\Components\Application\Request;
use Mesour\Components\Application\Url;
use Mesour\Components\ComponentModel\Container;
use Mesour\Components\Localization\ITranslator;
use Mesour\Components\Localization\NullTranslator;
use Mesour\Components\Security\Authorised;
use Mesour\Components\Security\IUser;
use Mesour\Components\Security\User;
use Mesour\InvalidStateException;

/**
 * @author Matouš Němec <http://mesour.com>
 */
class Application extends Container implements IApplication
{

	use Authorised;

	/**
	 * @var Request
	 */
	private $request;

	private $snippet = [];

	/**
	 * @var Url
	 */
	private $url;

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var Configuration
	 */
	private $configuration;

	private $isRunning = false;

	/**
	 * @param bool $need
	 * @return Request
	 */
	public function getRequest($need = true)
	{
		if ($need && !$this->request instanceof Request) {
			throw new InvalidStateException('Request is not set.');
		}
		return $this->request;
	}

	/**
	 * @return IUser
	 */
	public function getUser()
	{
		return $this->getContext()->getByType(IUser::class);
	}

	/**
	 * @return ITranslator
	 */
	public function getTranslator()
	{
		return $this->getContext()->getByType(ITranslator::class);
	}

	/**
	 * @param string|string[] $userRole
	 * @return $this
	 * @deprecated
	 */
	public function setUserRole($userRole)
	{
		$this->getUser()->setRoles($userRole);
		return $this;
	}

	/**
	 * @return Url
	 */
	public function getUrl()
	{
		if (!$this->url) {
			$this->url = new Url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
		}
		return $this->url;
	}

	public function setUrl(Url $url)
	{
		$this->url = $url;
		return $this;
	}

	public function isAjax()
	{
		return $this->request->getHeader('X-Requested-With') === 'XMLHttpRequest';
	}

	public function isPost()
	{
		return $_SERVER['REQUEST_METHOD'] === 'POST';
	}

	public function createLink(Control $control, $handle, $args = [])
	{
		return $this->getUrl()->create($control, $handle, $args);
	}

	public function createLinkName()
	{
		return $this->getName();
	}

	public function setSnippet($id, Control $control)
	{
		$this->snippet[$id] = $control;
		return $this;
	}

	public function getSnippets()
	{
		return $this->snippet;
	}

	public function setRequest(array $request)
	{
		if ($this->isRunning) {
			throw new InvalidStateException('Can not set request if application running.');
		}
		$this->request = new Request($request);
		return $this;
	}

	public function getContext()
	{
		if (!$this->context) {
			$this->context = new Context();
			$this->initializeDefaultContext();
		}
		return $this->context;
	}

	public function getConfiguration()
	{
		if (!$this->configuration) {
			$this->configuration = new Configuration();
		}
		return $this->configuration;
	}

	/**
	 * @return static|null
	 */
	public function getApplication()
	{
		return $this;
	}

	public function run()
	{
		if ($this->isRunning) {
			throw new InvalidStateException('Application is running. Can not run again.');
		}
		$this->isRunning = true;
	}

	private function initializeDefaultContext()
	{
		$nullTranslator = new NullTranslator();
		$this->context->setService($nullTranslator, ITranslator::class);
		$this->context->setService($nullTranslator);
		$this->context->setService(new User(), IUser::class);
	}

}
