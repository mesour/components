<?php

namespace TestNetteBridgeModule;

use Nette\Application\UI\Presenter;
use Tester\Assert;

class TestNettePresenter extends Presenter
{

    public function _saveGlobalState()
    {
        $this->saveGlobalState();
    }

}