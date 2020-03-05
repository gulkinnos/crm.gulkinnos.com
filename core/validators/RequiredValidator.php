<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 12:15 PM
 */
namespace Core\Validators;
use Core\Validators\CustomValidator;

class RequiredValidator extends CustomValidator
{
	public function runValidation(){
		$value = $this->_model->{$this->field};
		return (!empty($value));
	}
}