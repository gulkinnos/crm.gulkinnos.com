<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 12:16 PM
 */
namespace Core\Validators;
use Core\Validators\CustomValidator;

class MatchesValidator extends CustomValidator
{
	public function runValidation(){
		$value = $this->_model->{$this->field};
		return $value == $this->rule;
	}
}