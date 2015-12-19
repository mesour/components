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
abstract class AttributesControl extends OptionsControl implements IAttributesControl
{

    private $attributes = [];

    private $translatedArguments = ['title', 'data-confirm', 'data-title'];

    /** @var Mesour\Components\Utils\Html|null */
    private $htmlElement = '';

    public function setAttributes(array $attributes)
    {
        $this->attributes = [];
        foreach ($attributes as $key => $attribute) {
            $arguments = [$key];
            if (is_array($attribute)) {
                $arguments = array_merge($arguments, array_values($attribute));
            } else {
                $arguments[] = $attribute;
            }
            Mesour\Components\Utils\Helpers::invokeArgs([$this, 'setAttribute'], $arguments);
        }
        return $this;
    }

    public function setAttribute($key, $value, $append = FALSE, $translated = FALSE)
    {
        if ($translated) {
            $this->translatedArguments[] = $key;
            $this->translatedArguments = array_unique($this->translatedArguments);

            $value = $this->getTranslator()->translate($value);
        }
        Mesour\Components\Utils\Helpers::createAttribute($this->attributes, $key, $value, $append);

        if ($this->htmlElement) {
            $this->htmlElement->{$key}($this->getAttribute($key, FALSE));
        }
        return $this;
    }

    /**
     * @param bool|FALSE $isDisabled
     * @return array
     * @throws Mesour\InvalidArgumentException
     */
    public function getAttributes($isDisabled = FALSE)
    {
        $data = $this->getOption('data');

        if ($data && count($data) > 0) {
            foreach ($this->attributes as $key => $value) {
                if ($value instanceof Mesour\Components\Link\IUrl) {
                    continue;
                }
                $this->setAttribute($key, trim(Mesour\Components\Utils\Helpers::parseValue($value, $data)));
            }
        }

        foreach ($this->attributes as $key => $value) {
            if (!$isDisabled && $value instanceof Mesour\Components\Link\IUrl) {
                $this->setAttribute($key, $value->create($data));
            } elseif ($isDisabled && $value instanceof Mesour\Components\Link\IUrl) {
                $this->removeAttribute($key);
                continue;
            }
        }
        if ($isDisabled) {
            $this->removeAttribute('onclick');
            $this->setAttribute('class', 'disabled', TRUE);
        }
        return $this->attributes;
    }

    public function removeAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            if ($this->htmlElement) {
                unset($this->htmlElement->attrs{$key});
            }
            unset($this->attributes[$key]);
        }
        return $this;
    }

    public function getAttribute($key, $need = TRUE)
    {
        if (!$this->hasAttribute($key)) {
            if ($need) {
                throw new Mesour\OutOfRangeException('Attribute ' . $key . ' does not exist.');
            }
            return NULL;
        }
        return $this->attributes[$key];
    }

    protected function setHtmlElement(Mesour\Components\Utils\Html $htmlElement)
    {
        $this->htmlElement = $htmlElement;
        $this->setAttributes($htmlElement->attrs);
        return $htmlElement;
    }

    /**
     * @return Mesour\Components\Utils\Html|null
     */
    protected function getHtmlElement()
    {
        return $this->htmlElement;
    }

    protected function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __clone()
    {
        $this->htmlElement = clone $this->htmlElement;
        foreach ($this->attributes as $key => $attribute) {
            if (is_object($attribute)) {
                $this->attributes[$key] = clone $attribute;
            }
        }
        parent::__clone();
    }

}
