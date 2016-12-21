<?php

/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015-2016 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Utils;

use Mesour;
use Nette;

/**
 * HTML helper.
 *
 * <code>
 * $el = Html::el('a')->href($link)->setText('Mesour');
 * $el->class = 'myclass';
 * echo $el;
 *
 * echo $el->startTag(), $el->endTag();
 * </code>
 *
 * @author Matouš Němec <http://mesour.com>
 *
 * @property string|null $style
 * @property string|null $class
 * @property string|null $src
 * @property string|null $id
 * @property string|null $target
 * @property string|null $type
 * @property string|null $placeholder
 * @property string|null $href
 *
 * @method $this style($style, $append = false) Add style
 * @method $this class($class, $append = false) Add src
 * @method $this src($src) Add src
 * @method $this id($id) Set ID attribute
 * @method $this target($target) Set target attribute
 * @method $this type($type) Set type attribute
 * @method $this placeholder($placeholder) Set placeholder attribute
 * @method $this href($href) Set placeholder attribute
 *
 * @method static Html el($name = null, $attrs = null)
 */
class Html extends Nette\Utils\Html implements IString
{

	/**
	 * Adds new element's child.
	 * @param Html|string $child  Html node or raw HTML string
	 * @return static
	 */
	public function add($child)
	{
		return $this->addHtml($child);
	}

	/**
	 * Inserts child node.
	 * @param int|NULL $index position of NULL for appending
	 * @param Html|string $child Html node or raw HTML string
	 * @param bool $replace
	 * @return self
	 * @throws Nette\InvalidArgumentException
	 */
	public function insert($index, $child, $replace = false)
	{
		if ($child instanceof self || is_scalar($child) || $child instanceof IString) {
			if ($index === null) { // append
				$this->children[] = $child;

			} else { // insert or replace
				array_splice($this->children, (int) $index, $replace ? 1 : 0, [$child]);
			}

		} else {
			throw new Nette\InvalidArgumentException(sprintf('Child node must be scalar or Html object, %s given.', is_object($child) ? get_class($child) : gettype($child)));
		}

		return $this;
	}

}
