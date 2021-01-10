<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 22/09/2018 14:10
 */

namespace Phact\Application;

use Phact\Module\Module;

interface ModulesInterface
{
    /**
     * Return initialized instance of \Phact\Module\Module by name
     *
     * @param $name
     * @return Module
     */
    public function getModule($name);

    /**
     * Return list of modules
     *
     * @return Module[]
     */
    public function getModules();

    /**
     * Get modules names
     * @return string[]
     */
    public function getModulesList();
}