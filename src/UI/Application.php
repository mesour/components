<?php
/**
 * This file is part of the Mesour components (http://components.mesour.com)
 *
 * Copyright (c) 2015 Matouš Němec (http://mesour.com)
 *
 * For full licence and copyright please view the file licence.md in root of this project
 */

namespace Mesour\UI;

use Mesour\Components\Application\IApplication;
use Mesour\Components\Application\Request;
use Mesour\Components\Application\Url;
use Mesour\Components\BadStateException;
use Mesour\Components\BaseControl;



/**
 * @author Matouš Němec <matous.nemec@mesour.com>
 */
class Application extends BaseControl implements IApplication
{

    /**
     * @var Request
     */
    private $request;

    private $snippet = array();

    /**
     * @var Url
     */
    private $url;

    private $is_running = FALSE;

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        if (!$this->url) {
            $this->url = new Url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
        }
        return $this->url;
    }

    public function setUrl(Url $url)
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

    public function createLink(Control $control, $handle, $args = array())
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
            throw new BadStateException('Can not set request if application running.');
        }
        $this->request = new Request($request);
        return $this;
    }

    public function run()
    {
        if ($this->is_running) {
            throw new BadStateException('Application is running. Can not run again.');
        }
        $this->is_running = TRUE;
    }

}
