<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 12:17 PM
 */
namespace Core\Validators;
use Core\Validators\CustomValidator;

class EmailValidator extends CustomValidator
{
	public function runValidation(){
		$email = $this->_model->{$this->field};
		$pass = false;
		if(!empty($email)){
			$pass = filter_var($email, FILTER_VALIDATE_EMAIL);
		}
		return $pass;
	}
}