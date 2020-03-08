<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 */

namespace Core\Validators;

class RequiredValidator extends CustomValidator
{
	/**
	 * @override CustomValidator::runValidation()
	 *
	 * @return bool
	 */
	public function runValidation()
	{
		$value = $this->_model->{$this->field};
		return (!empty($value));
	}
}