<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Security;

use Mesour\Components\InvalidArgumentException;
use Mesour\Components\Link\IUrl;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
class Auth implements IAuth
{

    protected $resource = NULL;

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
