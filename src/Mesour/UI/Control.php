<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour\Components;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Control extends Components\BaseControl implements Components\IString
{

    const SNIPPET_PREFIX = 'm_snippet-';

    private $resource = NULL;

    public function createLink($handle, $args = [])
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
