<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Application;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Url
{

    protected $destination;

    protected $args = [];

    protected $users_args = [];

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

    public function create(Mesour\UI\Control $control, $handle, $args = [])
    {
        if (!is_string($handle)) {
            throw new Mesour\InvalidArgumentException(
                sprintf('Second parameter handle must be string. %s given.', gettype($handle))
            );
        }
        $link_name = $control->createLinkName();

        $new_args = [];
        foreach ($args as $key => $value) {
            $new_args['m_' . $link_name . '-' . $key] = $value;
        }

        $args = array_merge_recursive($new_args, $this->users_args);

        $args['m_do'] = $link_name . '-' . $handle;
        return $this->createUrl($args);
    }

    protected function createUrl($args = [])
    {
        $query = http_build_query($args);
        return $this->destination . (
        count($args) > 0
            ? (strpos($this->destination, '?') !== FALSE ? '&' : '?')
            : ''
        ) . $query;
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
