<?php

namespace Core;

use Core\FormHelpers;
use Core\Helpers;
use Core\Router;


class Input
{
	/**
	 * Checks if request method is POST.
	 * @return bool
	 */
	public function isPost()
	{
		return ($this->getRequestMethod() === 'POST');
	}

	/**
	 * Checks if request method is PUT.
	 * @return bool
	 */
	public function isPut()
	{
		return ($this->getRequestMethod() === 'PUT');
	}

	/**
	 * Checks if request method is GET.
	 * @return bool
	 */
	public function isGet()
	{
		return ($this->getRequestMethod() === 'GET');
	}

	/**
	 * Gets request method.
	 * @return string
	 */
	public function getRequestMethod()
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Sanitizes entire $_REQUEST and returns array if $input was not given.
	 * Sanitize and returns $_REQUEST[$input] as string or '' if input name was given in $input parameter.
	 * @param bool $input
	 *
	 * @return array|string
	 */
	public function get($input = false)
	{
		if (!$input) {
			// return entire request array and sanitize it
			$data = [];
			foreach ($_REQUEST as $field => $value) {
				$data[$field] = FormHelpers::sanitize($value);
			}
			return $data;
		}
		if (!isset($_REQUEST[$input])) {
			return '';
		}
		else {
			return FormHelpers::sanitize($_REQUEST[$input]);
		}
	}

	/**
	 * Redirects to "Bad token" page if token check failed.
	 * @return bool
	 */
	public function csrfCheck()
	{
		if (!FormHelpers::checkToken($this->get('csrf_token'))) {
			Router::redirect('restricted/badToken');
		}
		return true;
	}
}