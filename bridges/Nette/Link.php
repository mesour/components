<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Bridges\Nette;

use Mesour\Components\Helper;
use Mesour\Components\Link\ILink;
use Nette\Application\UI\PresenterComponent;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Link implements ILink
{

    /**
     * @var PresenterComponent
     */
    private $component;

    public function __construct(PresenterComponent $component) {
        $this->component = $component;
    }

    public function link($destination, $args = array()) {
        return $this->component->link($destination, $args);
    }

    public function create($destination, $args = array()) {
        return new Url($this, $destination, $args);
    }

}
