<?php

namespace Core\Validators;

use Core\Model;
use Exception;

/**
 * @abstract Base class for Validators.
 * runValidation() method is expected to be implemented in the extended class.
 */
abstract class CustomValidator
{
	/** @var bool $success */
	public $success = true;

	/** @var string $msg */
	public $msg = '';

	/** @var string $field */
	public $field;

	/** @var string|int|mixed */
	public $rule;

	/** @var Model $_model */
	protected $_model;

	abstract public function runValidation();

	/**
	 * CustomValidator constructor.
	 * @param Model $model
	 * @param array $params - ['field'],['msg'], and ['rule'](opt) indexes are expected
	 * @throws Exception
	 */
	public function __construct($model, $params)
	{
		$this->_model = $model;

		if (!array_key_exists('field', $params)) {
			throw new Exception("You must add a field to the params array.");
		}
		else {
			$this->field = (is_array($params['field'])) ? $params['field'][0] : $params['field'];
		}

		if (!property_exists($model, $this->field)) {
			throw new Exception("The field must exist in the model");
		}

		if (!array_key_exists('msg', $params)) {
			throw new Exception("You must add a msg to the params array.");
		}
		else {
			$this->msg = $params['msg'];
		}

		if (array_key_exists('rule', $params)) {
			$this->rule = $params['rule'];
		}

		try {
			$this->success = $this->runValidation();
		} catch (Exception $e) {
			echo "Validation Exception on " . get_class() . ": " . $e->getMessage() . "<br/>";
		}
	}

}