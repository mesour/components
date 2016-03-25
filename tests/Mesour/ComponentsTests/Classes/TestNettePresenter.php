<?php

namespace Mesour\ComponentsTests\Classes;

use Nette\Application\UI\Presenter;

class TestNettePresenter extends Presenter
{

	public function _saveGlobalState()
	{
		$this->saveGlobalState();
	}

}
