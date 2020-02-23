<?php


class Session
{
	public static function exists($name) {
		return isset($_SESSION[$name]);
	}

	public static function get($name) {
		return (self::exists($name) ? $_SESSION[$name] : null);
	}

	public static function set($name, $value) {
		return $_SESSION[$name] = $value;
	}

	public static function delete($name) {
		if(self::exists($name)) {
			unset($_SESSION[$name]);
		}
	}

	public static function uagent_no_version() {
		$regx = '/\/[a-zA-Z0-9.]+/';
		return preg_replace($regx, '', $_SERVER['HTTP_USER_AGENT']);
	}

}