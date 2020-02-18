<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/20/2018
 * Time: 7:45 PM
 */

define('DEBUG', true);

define('DB_NAME', 'db_name'); //database name
define('DB_USER', 'username'); // database username
define('DB_PASSWORD', 'password'); //database password
define('DB_HOST', '127.0.0.1'); // database host ***use IP address to avoid DNS lookup


define('DEFAULT_CONTROLLER', 'Home'); //default controller if there isn`t one defined in the url
define('DEFAULT_LAYOUT', 'default'); //If no layout is set in the controller use this layout

define('PROOT', '/'); //set this to '/' for a life server.

define('SITE_TITLE', 'Gulkinnos MVC Framework'); // This will be used if no site title is set
