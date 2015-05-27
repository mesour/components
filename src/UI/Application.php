<?php
/**
 * Mesour Components
 *
 * @license LGPL-3.0 and BSD-3-Clause
 * @copyright (c) 2015 Matous Nemec <matous.nemec@mesour.com>
 */

namespace Mesour\UI;

use Mesour\Components\Application\IApplication;
use Mesour\Components\Application\Request;
use Mesour\Components\Application\Url;
use Mesour\Components\BadStateException;

/**
 * @author mesour <matous.nemec@mesour.com>
 * @package Mesour Components
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
