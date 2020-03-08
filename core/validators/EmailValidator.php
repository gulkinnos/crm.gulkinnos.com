<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 */

namespace Core\Validators;

class EmailValidator extends CustomValidator
{
	/**
	 * @override CustomValidator::runValidation()
	 *
	 * @return bool
	 */
	public function runValidation()
	{
		$email = $this->_model->{$this->field};
		$pass  = false;
		if (!empty($email)) {
			$pass = filter_var($email, FILTER_VALIDATE_EMAIL);
		}
		return $pass;
	}
}