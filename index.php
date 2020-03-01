<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

// load configuration and helper functions
require_once(ROOT . DS . 'config' . DS . 'config.php');

//Autoload classes
function autoload($className) {
	if(file_exists(ROOT . DS . 'core' . DS . $className . '.php')) {
		require_once(ROOT . DS . 'core' . DS . $className . '.php');
	}
	elseif(file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . $className . '.php')) {
		require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . $className . '.php');
	}
	elseif(file_exists(ROOT . DS . 'app' . DS . 'models' . DS . $className . '.php')) {
		require_once(ROOT . DS . 'app' . DS . 'models' . DS . $className . '.php');
	}
	elseif(file_exists(ROOT . DS . 'app' . DS . 'custom_validators' . DS . $className . '.php')) {
		require_once(ROOT . DS . 'app' . DS . 'custom_validators' . DS . $className . '.php');
	}
	elseif(file_exists(ROOT . DS . 'core' . DS . 'validators' . DS . $className . '.php')) {
		require_once(ROOT . DS . 'core' . DS . 'validators' . DS . $className . '.php');
	}
}

spl_autoload_register('autoload');
session_start();

//Helpers::turnOnErrorReporting();

$url = isset($_SERVER['REQUEST_URI']) ? explode('/', ltrim($_SERVER['REQUEST_URI'], '/')) : [];



if(!Session::exists(CURRENT_USER_SESSION_NAME) && Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
	Users::loginUserFromCookie();
}


//Route the request
Router::route($url);
