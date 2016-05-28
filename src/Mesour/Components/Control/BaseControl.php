<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Control;

use Mesour;
use Mesour\Components\Link\ILink;
use Mesour\Components\Application\IPayload;
use Mesour\Components\Session\ISession;

/**
 * @author Matouš Němec <http://mesour.com>
 *
 * @method Mesour\Components\Control\BaseControl getParent()
 */
abstract class BaseControl extends Mesour\Components\ComponentModel\Container implements Mesour\Components\Utils\IString
{

	const SNIPPET_PREFIX = 'm_snippet-';

	const HANDLER_PREFIX = 'handle';

	public function beforeRender()
	{
		list($handle, $control) = $this->getCurrentHandler();
		if ($handle !== false) {
			$methodName = self::HANDLER_PREFIX . $handle;

			/** @var Mesour\UI\Control $control */
			if ($control && $control->getReflection()->hasMethod($methodName) && $this === $control) {
				$this->callHandler($methodName);
			} elseif ($this === $control) {
				throw new Mesour\Components\BadRequestException(
					sprintf('Invalid request. No handler for "handle%s".', ucfirst($handle))
				);
			}
		}
	}

	public function createLinkName()
	{
		$parent = $this->getParent();
		return $parent instanceof self || $parent instanceof Mesour\UI\Application
			? ($this->getParent()->createLinkName() . '-' . $this->getName())
			: $this->getName();
	}

	/**
	 * @param string $destination
	 * @param array $args
	 * @param null|mixed $optional
	 * @return Mesour\Components\Link\IUrl
	 */
	public function link($destination, $args = [], $optional = null)
	{
		return $this->getLink()->create($destination, $args, $optional);
	}

	/**
	 * @return Mesour\Components\Link\ILink
	 */
	public function getLink()
	{
		$context = $this->getApplication()->getContext();
		if (!$context->hasService(ILink::class)) {
			$context->setService(new Mesour\Components\Link\Link(), ILink::class);
		}
		return $context->getByType(ILink::class);
	}

	/**
	 * @return Mesour\Components\Application\IPayload
	 */
	public function getPayload()
	{
		$context = $this->getApplication()->getContext();
		if (!$context->hasService(IPayload::class)) {
			$context->setService(new Mesour\Components\Application\Payload(), IPayload::class);
		}
		return $context->getByType(IPayload::class);
	}

	/**
	 * @param bool $need
	 * @return Mesour\Components\Session\ISession|null
	 */
	public function getSession($need = true)
	{
		$context = $this->getApplication()->getContext();
		if (!$context->hasService(ISession::class) && $need) {
			$context->setService(new Mesour\Components\Session\Session(), ISession::class);
		}
		return $context->hasService(ISession::class) ? $context->getByType(ISession::class) : null;
	}

	/**
	 * @param bool $need
	 * @return Mesour\UI\Application|null
	 * @throws Mesour\InvalidStateException
	 */
	public function getApplication($need = true)
	{
		if ($this instanceof Mesour\Components\Application\IApplication) {
			return $this;
		}
		$parent = $this->getParent();
		if ($parent instanceof Mesour\Components\Application\IApplication) {
			return $parent;
		}
		if (!$parent) {
			if ($need) {
				throw new Mesour\InvalidStateException('Component ' . $this->getName() . ' is not attached to Application.');
			}
			return null;
		} else {
			return $this->getParent()->getApplication($need);
		}
	}

	public function createSnippet()
	{
		$name = self::SNIPPET_PREFIX . $this->createLinkName();
		//$this->getApplication()->setSnippet($name, $this);
		return Mesour\Components\Utils\Html::el('div', ['id' => $name]);
	}

	public function create()
	{
		$this->beforeRender();
		if ($this->getSession(false)) {
			$this->getSession()->saveState();
		}
		return '';
	}

	public function render()
	{
		$created = $this->create();
		return is_object($created) && $created !== $this && method_exists($created, 'render')
			? $created->render()
			: $created;
	}

	public function __toString()
	{
		try {
			return $this->render();
		} catch (\Exception $e) {
			trigger_error($e->getMessage(), E_USER_WARNING);
			return '';
		}
	}

	/**
	 * @return array  list($handle, $control) [string|FALSE, Mesour\Components\Control\IControl|null]
	 */
	protected function getCurrentHandler()
	{
		$app = $this->getApplication(false);
		if ($app) {
			/** @var Mesour\UI\Application $app */
			$do = str_replace('m_', '', $app->getRequest()->get('m_do'));
			if (strlen($do) > 0) {
				$exploded = explode('-', $do);

				$appKey = array_search($app->getName(), $exploded);
				if ($appKey !== false) {
					unset($exploded[$appKey]);
					$exploded = array_values($exploded);
				} else {
					return [false, null];
				}

				$current = null;
				$x = 0;
				$handle = false;
				foreach ($exploded as $item) {
					if ($x === 0) {
						if (!isset($app[$item])) {
							break;
						}
						$current = $app[$item];
					} elseif ($x < count($exploded)) {
						if (!isset($current[$item])) {
							$handle = $item;
							break;
						}
						$current = $current[$item];
					}
					$x++;
				}
				return [$handle, $current];
			}
		}
		return [false, null];
	}

	/**
	 * Called only if called component === $this and handler exists
	 * @param string $methodName
	 */
	private function callHandler($methodName)
	{
		$method = $this->getReflection()->getMethod($methodName);
		$parameters = $method->getParameters();
		$args = [];
		foreach ($parameters as $parameter) {
			$name = $parameter->getName();
			$parsedName = $this->createLinkName() . '-' . $name;
			$currentValue = $this->getApplication()->getRequest()->get('m_' . $parsedName);
			if (!is_null($currentValue)) {
				if (
					($parameter->isArray() || ($parameter->isDefaultValueAvailable() && is_array($parameter->getDefaultValue())))
					&& !is_array($currentValue)
				) {
					throw new Mesour\UnexpectedValueException(
						sprintf('Invalid request. Argument must be an array. "%s" given.', gettype($currentValue))
					);
				}
				$value = $currentValue;
			} else {
				if ($parameter->isDefaultValueAvailable()) {
					$value = $parameter->getDefaultValue();
				} else {
					throw new Mesour\InvalidArgumentException(
						sprintf('Invalid request. Required parameter %s doest not exists.', $parsedName)
					);
				}
			}
			$args[] = $value;
		}
		Mesour\Components\Utils\Helpers::invokeArgs([$this, $methodName], $args);
		$this->getSession()->saveState();
	}

}
