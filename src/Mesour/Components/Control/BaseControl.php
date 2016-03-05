<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Control;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 *
 * @method Mesour\Components\Control\BaseControl getParent()
 */
abstract class BaseControl extends Mesour\Components\ComponentModel\Container implements Mesour\Components\Utils\IString
{

	const SNIPPET_PREFIX = 'm_snippet-';

	const HANDLER_PREFIX = 'handle';

	/** @var Mesour\Components\Security\IAuthorizator|null */
	private $authorizator = null;

	/** @var string */
	private $iconClass = null;

	/** @var Mesour\Components\Session\ISession|null */
	private $session = null;

	/** @var Mesour\Components\Application\IPayload */
	private $payload = null;

	/** @var Mesour\Components\Localization\ITranslator|null */
	private $translator = null;

	private $nullTranslator = null;

	private $disabledTranslate = false;

	/** @var Mesour\Components\Link\ILink|null */
	private $link;

	private $userRole = null;

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
		return $this->getParent() instanceof self ? $this->getParent()->createLinkName() . '-' . $this->getName() : $this->getName();
	}

	public function setLink(Mesour\Components\Link\ILink $link)
	{
		$this->link = $link;
		return $this;
	}

	/**
	 * @param $destination
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
		$parent = $this->getParent();
		if (!$this->link) {
			if ($parent instanceof self) {
				return $parent->getLink();
			} else {
				return $this->link = new Mesour\Components\Link\Link;
			}
		}
		return $this->link;
	}

	public function setPayload(Mesour\Components\Application\IPayload $payload)
	{
		$this->payload = $payload;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUserRole()
	{
		$parent = $this->getParent();
		if (!$this->userRole) {
			if ($parent instanceof self) {
				return $parent->getUserRole();
			} else {
				return $this->userRole = 'guest';
			}
		}
		return $this->userRole;
	}

	public function setUserRole($userRole)
	{
		$this->userRole = $userRole;
		return $this;
	}

	/**
	 * @return Mesour\Components\Application\IPayload
	 */
	public function getPayload()
	{
		$parent = $this->getParent();
		if (!$this->payload) {
			if ($parent instanceof self) {
				return $parent->getPayload();
			} else {
				return $this->payload = new Mesour\Components\Application\Payload;
			}
		}
		return $this->payload;
	}

	public function setSession(Mesour\Components\Session\ISession $session)
	{
		$this->session = $session;
		return $this;
	}

	/**
	 * @return Mesour\Components\Session\ISession|null
	 */
	public function getSession($need = true)
	{
		$parent = $this->getParent();
		if (!$this->session) {
			if ($parent instanceof self) {
				return $parent->getSession($need);
			} else {
				if ($need) {
					return $this->session = new Mesour\Components\Session\Session;
				} else {
					return null;
				}
			}
		}
		return $this->session;
	}

	/**
	 * @param bool $need
	 * @return Mesour\Components\Application\IApplication|null
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

	public function setTranslator(Mesour\Components\Localization\ITranslator $translator)
	{
		$this->translator = $translator;
		return $this;
	}

	/**
	 * @param bool|FALSE $fromChildren
	 * @return Mesour\Components\Localization\ITranslator
	 */
	public function getTranslator($fromChildren = false)
	{
		$parent = $this->getParent();
		if (!$this->translator && (!$this->disabledTranslate || ($this->disabledTranslate && $fromChildren))) {
			if ($parent instanceof self) {
				return $parent->getTranslator(true);
			} else {
				return $this->translator = new Mesour\Components\Localization\NullTranslator;
			}
		} elseif ($this->disabledTranslate && !$fromChildren) {
			if (!$this->nullTranslator) {
				$this->nullTranslator = new Mesour\Components\Localization\NullTranslator;
			}
			return $this->nullTranslator;
		}
		return $this->translator;
	}

	public function setDisableTranslate($disabled = true)
	{
		$this->disabledTranslate = (bool)$disabled;
		return $this;
	}

	public function setAuthorizator(Mesour\Components\Security\IAuthorizator $authorizator)
	{
		$this->authorizator = $authorizator;
		return $this;
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

	/**
	 * @return Mesour\Components\Security\IAuthorizator|Mesour\Components\Security\Permission
	 */
	public function getAuthorizator()
	{
		$parent = $this->getParent();
		if (!$this->authorizator) {
			if ($parent instanceof self) {
				return $parent->getAuthorizator();
			} else {
				return $this->authorizator = new Mesour\Components\Security\Permission;
			}
		}
		return $this->authorizator;
	}

	public function setIconClass($iconClass)
	{
		if (is_object($iconClass)) {
			if (!$iconClass instanceof Mesour\Icon\IIcon) {
				throw new Mesour\InvalidArgumentException(
					sprintf(
						'Class "%s" must implements interface "%s".',
						get_class($iconClass),
						Mesour\Icon\IIcon::class
					)
				);
			}
			$iconClass = get_class($iconClass);
		} else {
			if (!class_exists($iconClass)) {
				throw new Mesour\InvalidArgumentException(
					sprintf('Icon class "%s" does not exits.', $iconClass)
				);
			}
			$interfaces = class_implements($iconClass);
			if (!$interfaces || !count($interfaces) || !in_array(Mesour\Icon\IIcon::class, $interfaces)) {
				throw new Mesour\InvalidArgumentException(
					sprintf('Class "%s" must implements interface "%s".', $iconClass, Mesour\Icon\IIcon::class)
				);
			}
		}

		$this->iconClass = $iconClass;
		return $this;
	}

	/**
	 * @param $type
	 * @return Mesour\Icon\IIcon
	 */
	protected function createNewIcon($type)
	{
		/** @var Mesour\Icon\IIcon $icon */
		$className = $this->getIconClass();
		$icon = new $className;
		$icon->setType($type);
		return $icon;
	}

	public function getIconClass()
	{
		$parent = $this->getParent();
		if (!$this->iconClass) {
			if ($parent instanceof self) {
				return $parent->getIconClass();
			} else {
				if (!class_exists(Mesour\UI\Icon::class)) {
					throw new Mesour\InvalidArgumentException(sprintf(
						'For using icons, install mesour/icon package. Class "%s" does not exists.',
						Mesour\UI\Icon::class
					));
				}
				return $this->iconClass = Mesour\UI\Icon::class;
			}
		}
		return $this->iconClass;
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
		if ($app = $this->getApplication(false)) {
			/** @var Mesour\UI\Application $app */
			$do = str_replace('m_', '', $app->getRequest()->get('m_do'));
			if (strlen($do) > 0) {
				$exploded = explode('-', $do);

				if (($appKey = array_search($app->getName(), $exploded)) !== false) {
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
	 * @param $methodName
	 */
	private function callHandler($methodName)
	{
		$method = $this->getReflection()->getMethod($methodName);
		$parameters = $method->getParameters();
		$args = [];
		foreach ($parameters as $parameter) {
			$name = $parameter->getName();
			if ($parameter->isDefaultValueAvailable()) {
				$default_value = $parameter->getDefaultValue();
			}
			$parsed_name = $this->createLinkName() . '-' . $name;
			if (!is_null($_value = $this->getApplication()->getRequest()->get('m_' . $parsed_name))) {
				if (
					($parameter->isArray() || (isset($default_value) && is_array($default_value)))
					&& !is_array($_value)
				) {
					throw new Mesour\UnexpectedValueException(
						sprintf('Invalid request. Argument must be an array. "%s" given.', gettype($_value))
					);
				}
				$value = $_value;
			} else {
				if (isset($default_value)) {
					$value = $default_value;
				} else {
					throw new Mesour\InvalidArgumentException(
						"Invalid request. Required parameter \"$parsed_name\" doest not exists."
					);
				}
			}
			$args[] = $value;
		}
		Mesour\Components\Utils\Helpers::invokeArgs([$this, $methodName], $args);
		$this->getSession()->saveState();
	}

}
