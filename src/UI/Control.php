<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 *
 * @method Control getParent()
 */
class Control extends Components\BaseControl implements Components\IString
{

    const SNIPPET_PREFIX = 'm_snippet-';

    private $resource = NULL;

    public function createLink($handle, $args = array())
    {
        return $this->getApplication()->createLink($this, $handle, $args);
    }

    public function setResource($resource)
    {
        if (!is_string($resource) && !is_null($resource)) {
            throw new Components\InvalidArgumentException('Resource must be string or NULL. ' . gettype($resource) . ' given.');
        }
        $this->resource = $resource;
        return $this;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function __toString()
    {
        $this->render();
        return '';
    }

}
