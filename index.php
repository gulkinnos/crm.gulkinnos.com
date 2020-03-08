<?php

use Core\Session;
use Core\Cookie;
use Core\Router;
use App\Models\Users;
use Core\Helpers;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

/**
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//echo '<pre>';
/**/

// load configuration and helper functions
require_once(ROOT . DS . 'config' . DS . 'config.php');

//Autoload classes
function autoload($className)
{
	$classArray = explode('\\', $className);
	$class      = array_pop($classArray);
	$subPath    = strtolower(implode(DS, $classArray));
	$path       = ROOT . DS . $subPath . DS . $class . '.php';
	if (file_exists($path)) {
		require_once($path);
	}
}

spl_autoload_register('autoload');
session_start();

// Helpers::turnErrorReportingOn();

$url = isset($_SERVER['REQUEST_URI']) ? explode('/',
	ltrim($_SERVER['REQUEST_URI'], '/')) : [];

if (!Session::exists(CURRENT_USER_SESSION_NAME)
	&& Cookie::exists(REMEMBER_ME_COOKIE_NAME)
) {
	Users::loginUserFromCookie();
}

//Route the request
Router::route($url);
