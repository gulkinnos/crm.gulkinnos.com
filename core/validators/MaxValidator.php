<?php
/**
 * Created by PhpStorm.
 * User: aleksandrgolubev
 * Date: 3/3/20
 * Time: 12:12 PM
 */

class MaxValidator extends CustomValidator
{
	public function runValidation()
	{
		$value = $this->_model->{$this->field};
		return (mb_strlen($value) <= $this->rule);
	}
}