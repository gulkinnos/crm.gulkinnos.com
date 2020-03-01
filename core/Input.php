<?php


class Input
{
	public static function get($input) {
		if(isset($_POST[$input])) {
			return Helpers::sanitize($_POST[$input]);
		}
		elseif(isset($_GET[$input])) {
			return Helpers::sanitize($_GET[$input]);
		}
		return null;
	}
}