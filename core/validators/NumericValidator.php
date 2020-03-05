<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 12:19 PM
 */
namespace Core\Validators;
use Core\Validators\CustomValidator;

class NumericValidator extends CustomValidator
{
	public function runValidation(){
		$value = $this->_model->{$this->field};
		$pass = true;
		if(!empty($value)){
			$pass = is_numeric($value);
		}
		return $pass;
	}
}