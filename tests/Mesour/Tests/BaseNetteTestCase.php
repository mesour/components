<?php

namespace Mesour\Tests;

use Nette\Application\UI\Presenter;
use Tester\Assert;
use Nette\DI\Container;

abstract class BaseNetteTestCase extends BaseTestCase
{

    /** @var Container */
    private $container;

    /** @var \Nette\Http\Session */

    private $session;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->session = $container->getByType('Nette\Http\Session');
        $this->session->start();
    }

    /**
     * @param $name
     * @return Presenter
     */
    public function getPresenter($name)
    {
        $presenterFactory = $this->getContainer()->getByType('Nette\Application\IPresenterFactory');

        $presenter = $presenterFactory->createPresenter($name);
        $presenter->autoCanonicalize = FALSE;

        return $presenter;
    }

    /**
     * Resolves service by type
     * @param string $class class or interface
     * @param bool $need    throw exception if service doesn't exist?
     * @return object service or NULL
     */
    protected function getByType($class, $need = TRUE)
    {
        $service = $this->container->getByType($class, $need);
        Assert::type($class, $service);
        return $service;
    }

    /**
     * Gets the service object by name.
     * @param string $name
     * @return object
     */
    protected function getByName($name)
    {
        return $this->container->getService($name);
    }

    /**
     * @return \Nette\DI\Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \Nette\Http\Session
     */
    protected function getSession()
    {
        Assert::type('Nette\Http\Session', $this->session);
        return $this->session;
    }
}