<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/22/2018
 * Time: 1:28 PM
 */

class Controller extends Application
{
    protected $_controller, $_action;

    public $view;

    public function __construct($controller, $action)
    {
        parent::__construct();
        $this->_controller = $controller;
        $this->_action = $action;
        $this->view = new View;
    }


}