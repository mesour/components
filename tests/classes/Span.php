<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Tests;

use Mesour\Components\Html;
use Mesour\UI\Control;
use Tester\Assert;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Span extends Control
{

    /**
     * @var Html
     */
    private $wrapper;

    private $text = 'Default content';

    private $handleCalled = FALSE;

    /**
     * @return Html
     */
    public function getControlPrototype()
    {
        return !$this->wrapper ? ($this->wrapper = Html::el('span')) : $this->wrapper;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function create()
    {
        parent::create();

        $wrapper = $this->getControlPrototype();
        $wrapper->setText($this->text);

        return $wrapper;
    }

    public function handleChange($page)
    {
        $this->handleCalled = TRUE;
        Assert::same($page, '2');
    }

    public function handleChangeArray($page = array())
    {
        $this->handleCalled = TRUE;
        Assert::same($page, \HandlesTest::$testArr);
    }

    public function handleChangeArrayRequired(array $page)
    {
        $this->handleCalled = TRUE;
        Assert::same($page, \HandlesTest::$testArr);
    }

    public function assertHandleCalled()
    {
        Assert::true($this->handleCalled);
    }

}
