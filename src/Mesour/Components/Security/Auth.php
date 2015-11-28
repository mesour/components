<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Security;

use Mesour\Components\InvalidArgumentException;
use Mesour\Components\Link\IUrl;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Auth implements IAuth
{

    protected $resource = NULL;

    /**
     * @param null|string $resource
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setResource($resource)
    {
        if (!is_string($resource) && !is_null($resource)) {
            throw new InvalidArgumentException('Resource must be string or NULL. ' . gettype($resource) . ' given.');
        }
        $this->resource = $resource;
        return $this;
    }

    /**
     * Performs authorization.
     * @param IUrl $url
     * @return bool
     */
    public function isAllowed(IUrl $url)
    {
        return TRUE;
    }

}
