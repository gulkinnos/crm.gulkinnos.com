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
		die(var_dump($data));
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

	public static function turnErrorReportingOn() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
	public static function turnErrorReportingOff() {
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		error_reporting(0);
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

	public static function currentPage() {
		$currentPage = $_SERVER["REQUEST_URI"];
		if ($currentPage === PROOT || $currentPage === PROOT . 'home/index') {
			$currentPage = PROOT;
		}
		return $currentPage;
	}

	public static function getObjectProperties($object){
		return get_object_vars($object);
	}
}
