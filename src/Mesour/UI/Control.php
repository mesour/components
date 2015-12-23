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
 *
 * @method Mesour\Components\Control\IControl getParent()
 * @method Mesour\Components\Control\BaseControl getComponent($name, $need = TRUE)
 */
class Control extends Mesour\Components\Control\BaseControl implements Mesour\Components\Control\IControl
{

    const SNIPPET_PREFIX = 'm_snippet-';

    private $permission;

    public function createLink($handle, $args = [])
    {
        return $this->getApplication()->createLink($this, $handle, $args);
    }

    protected function setPermissionCheck(
        $resource = Mesour\Components\Security\IAuthorizator::ALL,
        $privilege = Mesour\Components\Security\IAuthorizator::ALL
    )
    {
        $this->permission = [$this->getUserRole(), $resource, $privilege];
    }

    public function isAllowed()
    {
        return !$this->permission || Mesour\Components\Utils\Helpers::invokeArgs([$this->getAuthorizator(), 'isAllowed'], $this->permission);
    }

}
