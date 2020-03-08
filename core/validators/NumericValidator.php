<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 */

namespace Core\Validators;

class NumericValidator extends CustomValidator
{
	/**
	 * @override CustomValidator::runValidation()
	 *
	 * @return bool
	 */
	public function runValidation()
	{
		$value = $this->_model->{$this->field};
		$pass  = true;
		if (!empty($value)) {
			$pass = is_numeric($value);
		}
		return $pass;
	}
}