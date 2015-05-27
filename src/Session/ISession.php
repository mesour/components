<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\Components\Session;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
 */
interface ISession
{

    /**
     * @param $section
     * @return ISessionSection
     */
    public function getSection($section);

    public function hasSection($section);

    public function remove();

    public function loadState();

    public function saveState();

}
