<?php

/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/22/2018
 * Time: 3:02 PM
 */
class Home extends Controller
{

    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }

    public function indexAction()
    {

        //$db = DB::getInstance();

        $this->view->render('home/index');

    }
}