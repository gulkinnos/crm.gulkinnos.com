<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/20/2018
 * Time: 7:28 PM
 */

class Helpers
{

	public static function dnd($data) {
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
		die;
	}

	public static function sanitize($dirtyValue) {
		return htmlentities($dirtyValue, ENT_QUOTES, 'UTF-8');
	}
}
