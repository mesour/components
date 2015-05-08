<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2013 - 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Link;
use Mesour\Components\Helper;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Url implements IUrl
{

    protected $destination;

    protected $args = array();

    /**
     * @var ILink
     */
    protected $link;

    public function __construct(ILink $link, $destination, $args = array()) {
        $this->destination = $destination;
        $this->args = $args;
        $this->link = $link;
    }

    public function create($data = array()) {
        if(count($data) > 0) {
            foreach($this->args as $key => $value) {
                $this->args[$key] = Helper::parseValue($value, $data);
            }
        }
        return $this->createUrl();
    }

    private function createUrl() {
        $query = http_build_query($this->args);
        return $this->destination . (strpos($this->destination, '?') !== FALSE ? '&' : '?') . $query;
    }

    public function __toString() {
        return $this->create();
    }

}
