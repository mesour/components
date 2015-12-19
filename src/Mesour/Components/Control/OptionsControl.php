<?php
/**
 * This file is part of the Mesour Button (http://components.mesour.com/component/button)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Control;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
abstract class OptionsControl extends Mesour\UI\Control implements IOptionsControl
{

    private $options = [];

    protected $defaults = [];

    private $privateDefaults = [
        'data' => []
    ];

    public function __construct($name = NULL, Mesour\Components\ComponentModel\IContainer $parent = NULL)
    {
        parent::__construct($name, $parent);

        foreach ($this->defaults as $key => $default) {
            $this->setOption($key, $default);
        }
        $this->options = array_merge($this->privateDefaults, $this->options);
    }

    public function setOption($key, $value, $subKey = NULL)
    {
        if ($key === 'data') {
            $this->checkDataValue($value);
        }
        if (!is_null($subKey)) {
            $this->options[$key][$subKey] = $value;
        } else {
            $this->options[$key] = $value;
        }
        return $this;
    }

    public function getOption($key, $subKey = NULL)
    {
        if (!$this->hasOption($key)) {
            throw new Mesour\OutOfRangeException("Option with key $key does not exists.");
        } elseif (!is_null($subKey)) {
            if (!is_array($this->options[$key])) {
                throw new Mesour\UnexpectedValueException("Option with key $key must be array.");
            }
            if (!isset($this->options[$key][$subKey])) {
                throw new Mesour\OutOfRangeException("Key $subKey does not exists on option with key $key.");
            }
            return $this->options[$key][$subKey];
        }
        return $this->options[$key];
    }

    public function hasOption($key)
    {
        return isset($this->options[$key]);
    }

    protected function checkDataValue($data)
    {
        if (
            !is_array($data)
            && ((class_exists(Mesour\Sources\ArrayHash::class)
                    && !$data instanceof Mesour\Sources\ArrayHash) || !class_exists(Mesour\Sources\ArrayHash::class))
        ) {
            throw new Mesour\InvalidArgumentException(
                sprintf("'Data must be array or instance of %s. %s given. ", Mesour\Sources\ArrayHash::class, gettype($data))
            );
        }
    }

}
