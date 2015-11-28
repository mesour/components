<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Security;

use Mesour\Components\Link\IUrl;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
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