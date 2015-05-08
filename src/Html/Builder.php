<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Builder
{
    /**
     * @var Html
     */
    private $wrapper;

    public function __construct($wrapper)
    {
        if ($wrapper instanceof Html) {
            $this->wrapper = $wrapper;
        } elseif (is_string($wrapper)) {
            $this->wrapper = Html::el($wrapper);
        } else {
            throw new InvalidArgumentException('Wrapper must be instance of \Mesour\Components\Html or string.');
        }
    }

    public function render()
    {
        echo $this->wrapper->render();
    }

    public function __toString()
    {
        return $this->wrapper->render();
    }

}
