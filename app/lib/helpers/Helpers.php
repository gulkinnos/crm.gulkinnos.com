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

	public static function vd($data = null)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}

	public static function sanitize($dirtyValue) {
		return htmlentities($dirtyValue, ENT_QUOTES, 'UTF-8');
	}

	public static function currentUser() {
		if(!is_null(Users::currentLoggedInUser())) {
			return Users::currentLoggedInUser();
		}
		else {
			return new DataModel();
		}
	}

	public static function turnOnErrorReporting() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	public static function postedValues($post) {
		if(!is_array($post) || empty($post)) {
			return [];
		}
		$cleanArray = [];
		foreach ($post as $key => $value) {
			$cleanArray[$key] = self::sanitize($value);
		}
		return $cleanArray;

	}


}
