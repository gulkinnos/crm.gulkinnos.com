<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/20/2018
 * Time: 7:57 PM
 */

class Router
{
    public static function route($url)
    {

        //controller
        $controller = DEFAULT_CONTROLLER;
        if (isset($url[0]) && $url[0] != '') {
            $controller = ucwords($url[0]);
            array_shift($url);
        }
        $controller_name = $controller;

        //action
        $action = 'indexAction';
        if (isset($url[0]) && $url[0] != '') {
            $action = ucwords($url[0] . 'Action');
            array_shift($url);
        }

        //params
        $queryParams = $url;

        if (class_exists($controller)) {
            $dispatch = new $controller($controller_name, $action);
        } else {
            die(var_dump('Class "' . $controller . '" does not exist'));
        }


        if (method_exists($controller, $action)) {
            call_user_func_array([$dispatch, $action], $queryParams);
        } else {
            die(var_dump('That method does not exist in the controller \"' . $controller_name . '\"'));
        }
    }
}