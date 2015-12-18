<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\Components;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
interface IControl extends IContainer, IComponent, IString
{

    public function createLink($handle, $args = array());

    public function setResource($resource);

    public function getResource();

    public function beforeRender();

    public function createLinkName();

    public function setLink(Link\ILink $link);

    /**
     * @param $destination
     * @param array $args
     * @return Link\IUrl
     */
    public function link($destination, $args = array());

    /**
     * @return Link\ILink
     */
    public function getLink();

    public function setPayload(Application\IPayload $payload);

    /**
     * @return Application\IPayload
     */
    public function getPayload();

    public function setSession(Session\ISession $session);

    /**
     * @return Session\ISession
     */
    public function getSession();

    /**
     * @param bool $need
     * @return Application\IApplication|null
     * @throws BadStateException
     */
    public function getApplication($need = TRUE);

    public function setTranslator(Localize\ITranslator $translator);

    /**
     * @return Localize\ITranslator
     */
    public function getTranslator();

    public function setAuth(Security\IAuth $auth);

    /**
     * @return Security\IAuth
     */
    public function getAuth();

    /**
     * @return Html
     */
    public function createSnippet();

    public function create();

}
