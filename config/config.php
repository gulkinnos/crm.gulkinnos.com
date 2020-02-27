<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/20/2018
 * Time: 7:45 PM
 */

define('DEBUG', true);

require_once 'config_db.php';

define('DEFAULT_CONTROLLER', 'HomeController'); //default controller if there isn`t one defined in the url
define('DEFAULT_LAYOUT', 'default'); //If no layout is set in the controller use this layout

define('PROOT', '/'); //set this to '/' for a life server.
define('MENU_BRAND', 'GN Menu'); //set this to '/' for a life server.

define('SITE_TITLE', 'Gulkinnos MVC Framework'); // This will be used if no site title is set

define('CURRENT_USER_SESSION_NAME','fsdfdsfdsfdfsd435435fdsffrt4545'); //session name for logged in users
define('REMEMBER_ME_COOKIE_NAME', 'dsfdfdfdf454543dgfgfd43'); //cookie name for logged in user remember me
define('REMEMBER_ME_COOKIE_EXPIRY', 2592000); //time in seconds for remember me cookie to live (30 days)
define('ACCESS_RESTRICTED', 'RestrictedController'); //Controller name for the restricted redirect
