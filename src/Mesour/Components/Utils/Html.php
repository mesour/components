<?php

/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
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
 * @author Matouš Němec <matous.nemec@mesour.com>
 *
 * @property string|null $style
 * @property string|null $class
 * @property string|null $src
 * @property string|null $id
 * @property string|null $target
 * @property string|null $type
 * @property string|null $placeholder
 *
 * @method $this style() style($style, $append = FALSE) Add style
 * @method $this class() class($src, $append = FALSE) Add src
 * @method $this src() src($src) Add src
 * @method $this id($src) Set ID attribute
 * @method $this target($src) Set target attribute
 * @method $this type($src) Set type attribute
 * @method $this placeholder($src) Set placeholder attribute
 *
 * @method static Html el($name = NULL, $attrs = NULL)
 */
class Html extends Nette\Utils\Html implements IString
{

    /**
     * Inserts child node.
     * @param  int|NULL position of NULL for appending
     * @param  Html|string Html node or raw HTML string
     * @param  bool
     * @return self
     * @throws Nette\InvalidArgumentException
     */
    public function insert($index, $child, $replace = FALSE)
    {
        if ($child instanceof self || is_scalar($child) || $child instanceof IString) {
            if ($index === NULL) { // append
                $this->children[] = $child;

            } else { // insert or replace
                array_splice($this->children, (int) $index, $replace ? 1 : 0, array($child));
            }

        } else {
            throw new Nette\InvalidArgumentException(sprintf('Child node must be scalar or Html object, %s given.', is_object($child) ? get_class($child) : gettype($child)));
        }

        return $this;
    }

}
