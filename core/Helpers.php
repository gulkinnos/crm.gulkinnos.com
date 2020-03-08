<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/20/2018
 * Time: 7:28 PM
 */
namespace Core;
class Helpers
{

	/**
	 * Prints given data using var_dump() and stop script execution.
	 * @param mixed $data - data to print
	 */
	public static function dnd($data) {
		echo '<pre>';
		die(var_dump($data));
	}

	/**
	 * Prints given data using var_dump() and continues script execution.
	 * @param mixed $data - data to print
	 */
	public static function vd($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}

	/**
	 * Turns on error reporting.
	 */
	public static function turnErrorReportingOn() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}

	/**
	 * Turns off error reporting.
	 */
	public static function turnErrorReportingOff() {
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		error_reporting(0);
	}

	/**
	 * Gets current page URI.
	 * @return string
	 */
	public static function currentPage() {
		$currentPage = $_SERVER["REQUEST_URI"];
		if ($currentPage === PROOT || $currentPage === PROOT . 'home/index') {
			$currentPage = PROOT;
		}
		return $currentPage;
	}

	/**
	 * Gets object properties as array.
	 * @param object $object
	 * @return array
	 */
	public static function getObjectProperties($object){
		return get_object_vars($object);
	}
}
