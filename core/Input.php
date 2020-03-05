<?php
namespace Core;
use Core\FormHelpers;
use Core\Helpers;
use Core\Router;


class Input
{
	/**
	 * @return bool
	 */
	public function isPost(){
		return ($this->getRequestMethod() === 'POST');
	}
	/**
	 * @return bool
	 */
	public function isPut(){
		return ($this->getRequestMethod() === 'PUT');
	}
	/**
	 * @return bool
	 */
	public function isGet(){
		return ($this->getRequestMethod() === 'GET');
	}

	/**
	 * @return string
	 */
	public function getRequestMethod(){
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * @param bool $input
	 *
	 * @return array|string
	 */
	public function get($input = false) {
		if(!$input) {
			// return entire request array and sanitize it
			$data = [];
			foreach ($_REQUEST as $field => $value) {
				$data[$field] = FormHelpers::sanitize($value);
			}
			return $data;
		}
		if(!isset($_REQUEST[$input])){
			return '';
		}else{
			return FormHelpers::sanitize($_REQUEST[$input]);
		}

	}

	/**
	 * @return bool
	 */
	public function csrfCheck() {
		if(!FormHelpers::checkToken($this->get('csrf_token'))) {
			Router::redirect('restricted/badToken');
		}
		return true;
	}
}