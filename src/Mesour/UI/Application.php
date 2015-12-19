<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Application extends Mesour\Components\Control\BaseControl implements Mesour\Components\Application\IApplication
{

    /**
     * @var Mesour\Components\Application\Request
     */
    private $request;

    private $snippet = [];

    /**
     * @var Mesour\Components\Application\Url
     */
    private $url;

    private $is_running = FALSE;

    /**
     * @return Mesour\Components\Application\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Mesour\Components\Application\Url
     */
    public function getUrl()
    {
        if (!$this->url) {
            $this->url = new Mesour\Components\Application\Url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
        }
        return $this->url;
    }

    public function setUrl(Mesour\Components\Application\Url $url)
    {
        $this->url = $url;
        return $this;
    }

    public function isAjax()
    {
        return $this->request->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function createLink(Control $control, $handle, $args = [])
    {
        return $this->getUrl()->create($control, $handle, $args);
    }

    public function setSnippet($id, Control $control)
    {
        $this->snippet[$id] = $control;
        return $this;
    }

    public function getSnippets()
    {
        return $this->snippet;
    }

    public function setRequest(array $request)
    {
        if ($this->is_running) {
            throw new Mesour\InvalidStateException('Can not set request if application running.');
        }
        $this->request = new Mesour\Components\Application\Request($request);
        return $this;
    }

    public function run()
    {
        if ($this->is_running) {
            throw new Mesour\InvalidStateException('Application is running. Can not run again.');
        }
        $this->is_running = TRUE;
    }

}