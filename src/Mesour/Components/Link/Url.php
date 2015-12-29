<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Link;

use Mesour;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Url implements IUrl
{

    protected $destination;

    protected $args = [];

    /**
     * @var ILink
     */
    protected $link;

    /**
     * @param ILink $link
     * @param string $destination
     * @param array $args
     * @throws Mesour\InvalidArgumentException
     */
    public function __construct(ILink $link, $destination, $args = [])
    {
        if (!is_string($destination)) {
            throw new Mesour\InvalidArgumentException(
                sprintf('Destination must be string. %s given.', gettype($destination))
            );
        }
        $this->destination = $destination;
        $this->args = $args;
        $this->link = $link;
    }

    /**
     * @param array $data
     * @return string
     */
    public function create($data = [])
    {
        if (count($data) > 0) {
            foreach ($this->args as $key => $value) {
                $this->args[$key] = Mesour\Components\Utils\Helpers::parseValue($value, $data);
            }
        }
        return $this->createUrl();
    }

    protected function createUrl()
    {
        $query = http_build_query($this->args);
        return $this->destination . (
        count($this->args) > 0
            ? (strpos($this->destination, '?') !== FALSE ? '&' : '?')
            : ''
        ) . $query;
    }

    /**
     * @return ILink
     */
    public function getLink()
    {
        return $this->link;
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
