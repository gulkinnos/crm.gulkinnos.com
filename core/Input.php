<?php


class Input
{
	public static function get($input) {
		if(isset($_POST[$input])) {
			return FormHelpers::sanitize($_POST[$input]);
		}
		elseif(isset($_GET[$input])) {
			return FormHelpers::sanitize($_GET[$input]);
		}
		return null;
	}
}