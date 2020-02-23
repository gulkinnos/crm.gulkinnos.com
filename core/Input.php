<?php


class Input
{
	public static function sanitize($dirtyValue) {
		return htmlentities($dirtyValue, ENT_QUOTES, 'UTF-8');
	}

	public static function get($input) {
		if(isset($_POST[$input])) {
			return self::sanitize($_POST[$input]);
		}
		elseif(isset($_GET[$input])) {
			return self::sanitize($_GET[$input]);
		}
		return null;
	}
}