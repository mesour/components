<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components\Control;

use Mesour;
use Mesour\Components\ComponentModel;


/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IControl extends ComponentModel\IContainer, ComponentModel\IComponent, Mesour\Components\Utils\IString
{

    public function createLink($handle, $args = []);

    public function beforeRender();

    public function createLinkName();

    public function setLink(Mesour\Components\Link\ILink $link);

    /**
     * @param $destination
     * @param array $args
     * @return Mesour\Components\Link\IUrl
     */
    public function link($destination, $args = []);

    /**
     * @return Mesour\Components\Link\ILink
     */
    public function getLink();

    public function setPayload(Mesour\Components\Application\IPayload $payload);

    /**
     * @return Mesour\Components\Application\IPayload
     */
    public function getPayload();

    public function setSession(Mesour\Components\Session\ISession $session);

    /**
     * @return Mesour\Components\Session\ISession
     */
    public function getSession();

    /**
     * @param bool $need
     * @return Mesour\Components\Application\IApplication|null
     * @throws Mesour\InvalidStateException
     */
    public function getApplication($need = TRUE);

    public function setTranslator(Mesour\Components\Localization\ITranslator $translator);

    /**
     * @param bool $fromChildren
     * @return Mesour\Components\Localization\ITranslator
     */
    public function getTranslator($fromChildren = FALSE);

    public function setDisableTranslate($disabled = TRUE);

    public function setAuthorizator(Mesour\Components\Security\IAuthorizator $auth);

    /**
     * @return Mesour\Components\Security\IAuthorizator
     */
    public function getAuthorizator();

    public function isAllowed();

    /**
     * @return Mesour\Components\Utils\Html
     */
    public function createSnippet();

    public function create();

    /**
     * @param Mesour\Components\Filter\Rules\RulesContainer|null $rulesContainer
     * @return Mesour\Components\Filter\FilterIterator
     * @internal
     */
    public function createFilterIterator(Mesour\Components\Filter\Rules\RulesContainer $rulesContainer = NULL);

}
