<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 12:16 PM
 */

class MatchesValidator extends CustomValidator
{
	public function runValidation(){
		$value = $this->_model->{$this->field};
		return $value == $this->rule;
	}
}