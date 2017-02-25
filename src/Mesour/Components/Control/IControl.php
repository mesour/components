<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2017 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Control;

use Mesour;
use Mesour\Components\ComponentModel;

/**
 * @author Matouš Němec <http://mesour.com>
 */
interface IControl extends ComponentModel\IContainer, ComponentModel\IComponent, Mesour\Components\Utils\IString
{

	public function createLink($handle, $args = []);

	public function beforeRender();

	public function createLinkName();

	/**
	 * @param string $destination
	 * @param array $args
	 * @param null|mixed $optional
	 * @return Mesour\Components\Link\IUrl
	 */
	public function link($destination, $args = [], $optional = null);

	/**
	 * @return Mesour\Components\Link\ILink
	 */
	public function getLink();

	/**
	 * @return Mesour\Components\Application\IPayload
	 */
	public function getPayload();

	/**
	 * @return Mesour\Components\Session\ISession
	 */
	public function getSession();

	/**
	 * @param bool $need
	 * @return Mesour\Components\Application\IApplication|null
	 */
	public function getApplication($need = true);

	/**
	 * @return Mesour\Components\Utils\Html
	 */
	public function createSnippet();

	public function create();

}
