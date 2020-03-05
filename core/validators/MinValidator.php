<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 9:23 AM
 */
namespace Core\Validators;
use Core\Validators\CustomValidator;

class MinValidator extends CustomValidator
{

	public function runValidation()
	{
		$value = $this->_model->{$this->field};
		return (mb_strlen($value) >= $this->rule);
	}
}