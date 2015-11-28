<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Application;

use Mesour\UI\Control;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IApplication
{

    public function getRequest();

    public function setRequest(array $request);

    /**
     * @return Url
     */
    public function getUrl();

    public function setUrl(Url $url);

    public function isAjax();

    public function isPost();

    public function createLink(Control $control, $handle, $args = array());

    public function run();

}
