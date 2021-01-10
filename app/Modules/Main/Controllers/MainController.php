<?php

namespace Modules\Main\Controllers;


use Phact\Controller\Controller;
use Phact\Exceptions\DependencyException;

/**
 * Class MainController
 * @package Modules\Main\Controllers
 */
class MainController extends Controller
{
    /**
     * @throws DependencyException
     */
    public function index(): void
    {
        echo $this->render('Main/index.tpl');
    }
}