<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Security;

use Mesour\Components\Link\IUrl;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface IAuth
{

    /**
     * @param string|null $resource
     * @return mixed
     */
    public function setResource($resource);

    /**
     * Performs authorization.
     * @param IUrl $url
     * @return bool
     */
    public function isAllowed(IUrl $url);

}