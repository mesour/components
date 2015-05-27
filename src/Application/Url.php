<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Application;

use Mesour\Components\InvalidArgumentException;
use Mesour\UI\Control;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Url
{

    protected $destination;

    protected $args = array();

    protected $users_args = array();

    public function __construct($request_uri)
    {
        $request_uri = urldecode($request_uri);
        if (strpos($request_uri, '?') !== FALSE) {
            $explode = explode('?', $request_uri);
            $this->destination = $explode[0];
            parse_str($explode[1], $this->args);
            foreach ($this->args as $key => $arg) {
                if (substr($key, 0, 2) !== 'm_') {
                    $this->users_args[$key] = $arg;
                }
            }
        } else {
            $this->destination = $request_uri;
        }
    }

    public function create(Control $control, $handle, $args = array())
    {
        if (!is_string($handle)) {
            throw new InvalidArgumentException('Second parameter handle must be string. ' . gettype($handle) . ' given.');
        }
        $link_name = $control->createLinkName();

        $new_args = array();
        foreach ($args as $key => $value) {
            $new_args['m_' . $link_name . '-' . $key] = $value;
        }

        $args = array_merge_recursive($new_args, $this->users_args);

        $args['m_do'] = $link_name . '-' . $handle;
        return $this->createUrl($args);
    }

    protected function createUrl($args = array())
    {
        $query = http_build_query($args);
        return $this->destination . (count($args) > 0 ? (strpos($this->destination, '?') !== FALSE ? '&' : '?') : '') . $query;
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

}
