<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Application;

use Mesour\UI\Control;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
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
