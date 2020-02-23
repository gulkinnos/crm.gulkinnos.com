<?php


class Cookie
{
	public static function set($name, $value, $expiry) {
		return setcookie($name, $value, time() + $expiry, '/');
	}

	public static function delete($name) {
		self::set($name, '', time() - 1);
	}

	public static function get($name) {
		return self::exists($name) ? $_COOKIE[$name] : null;
	}

	public static function exists($name) {
		return isset($_COOKIE[$name]);
	}


}