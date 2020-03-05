<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/20/2018
 * Time: 9:11 PM
 */
namespace Core;

class Application
{
    public function __construct()
    {
        $this->_set_reporting();
        $this->_unregister_globals();
    }

    private function _set_reporting()
    {
        if (DEBUG) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 0);
            ini_set('log_errors', 1);
            ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'errors.log');
        }

    }

    private function _unregister_globals()
    {
        if(ini_get('register_globals')){
            $globalsArray=['_SESSION', '_COOKIE', '_POST', '_GET', '_REQUEST', '_SERVER', '_ENV', '_FILES'];
            foreach ($globalsArray as $gl){
                foreach ($GLOBALS[$gl] as $k => $v){
                    if($GLOBALS[$k]===$v){
                        unset($GLOBALS[$k]);
                    }
                }
            }
        }
    }

}