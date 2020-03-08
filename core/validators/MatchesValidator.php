<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 */

namespace Core\Validators;


class MatchesValidator extends CustomValidator
{
	public function runValidation()
	{
		$value = $this->_model->{$this->field};
		return $value == $this->rule;
	}
}