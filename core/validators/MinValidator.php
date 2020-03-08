<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 */

namespace Core\Validators;

class MinValidator extends CustomValidator
{
	/**
	 * @override CustomValidator::runValidation()
	 *
	 * @return bool
	 */
	public function runValidation()
	{
		$value = $this->_model->{$this->field};
		return (mb_strlen($value) >= $this->rule);
	}
}