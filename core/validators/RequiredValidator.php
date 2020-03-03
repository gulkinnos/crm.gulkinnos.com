<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 12:15 PM
 */

class RequiredValidator extends CustomValidator
{
	public function runValidation(){
		$value = $this->_model->{$this->field};
		return (!empty($value));
	}
}