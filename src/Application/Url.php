<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Application;

use Mesour\Components\Helper;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Url
{

    protected $destination;

    protected $args = array();

    public function __construct($request_uri)
    {
        if(strpos($this->destination, '?') !== FALSE) {
            $explode = explode('?', $this->destination);
            $this->destination = $explode[0];
            parse_str($explode[1], $this->args);
        } else {
            $this->destination = $request_uri;
        }
    }

    public function create($data = array())
    {
        if (count($data) > 0) {
            foreach ($this->args as $key => $value) {
                $this->args[$key] = Helper::parseValue($value, $data);
            }
        }
        return $this->createUrl();
    }

    protected function createUrl()
    {
        $query = http_build_query($this->args);
        return $this->destination . (count($this->args) > 0 ? (strpos($this->destination, '?') !== FALSE ? '&' : '?') : '') . $query;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->args;
    }

    public function __toString()
    {
        return $this->create();
    }

}
