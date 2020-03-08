<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 */

namespace Core\Validators;

class MaxValidator extends CustomValidator
{
	public function runValidation()
	{
		$value = $this->_model->{$this->field};
		return (mb_strlen($value) <= $this->rule);
	}
}