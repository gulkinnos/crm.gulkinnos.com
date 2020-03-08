<?php

namespace Core;

class Cookie
{
	/**
	 * Sets cookie by name and value to the root path. Expects expiry time in seconds as integer.
	 * @param string $name - cookie name
	 * @param string $value - cookies value
	 * @param int $expiry - seconds to expire from current timestamp.
	 * @return bool - true on succces, false on fail
	 */
	public static function set($name, $value, $expiry)
	{
		return setcookie($name, $value, time() + $expiry, '/');
	}

	/**
	 * Deletes cookie by name setting expiration time to invalid value.
	 * @param string $name - cookie name
	 */
	public static function delete($name)
	{
		self::set($name, '', time() - 1);
	}

	/**
	 * Gets cookie value by name.
	 * @param string $name - cookie name.
	 * @return string | null
	 */
	public static function get($name)
	{
		return self::exists($name) ? $_COOKIE[$name] : null;
	}

	/**
	 * Checks does cookie with given name exist.
	 * @param string $name - cookie name
	 * @return bool
	 */
	public static function exists($name)
	{
		return isset($_COOKIE[$name]);
	}


}