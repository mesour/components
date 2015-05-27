<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Tests;

use Mesour\Components\Container;
use Mesour\Components\IContainer;
use Tester\Assert;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class TestComponent extends Container
{

    public $onTest = array();

    public $onNotArray;

    private $onPrivate = array();

    /**
     * @var IContainer
     */
    public $attachedContains;

    /**
     * @var IContainer
     */
    public $detachedContains;

    public function triggerTest()
    {
        $this->onTest($this);
    }

    public function triggerNotArray()
    {
        $this->onNotArray();
    }

    public function triggerPrivateProperty()
    {
        $this->onPrivate();
    }

    public function attached(IContainer $parent)
    {
        parent::attached($parent);
        $this->attachedContains = $parent;
    }

    /**
     * @param IContainer $parent
     */
    public function detached(IContainer $parent)
    {
        parent::attached($parent);
        $this->detachedContains = $parent;
    }

    public function assertAttached(IContainer $parent)
    {
        Assert::same($this->attachedContains, $parent);
    }

    public function assertDetached(IContainer $parent)
    {
        Assert::same($this->detachedContains, $parent);
    }

}
