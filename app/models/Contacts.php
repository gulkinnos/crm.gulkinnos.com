<?php


class Contacts extends Model
{
	public static $addValidation =
		[
			'fname' => [
				'display'  => "First Name",
				'required' => true,
				'max'      => 155
			],
			'lname' => [
				'display'  => "Last Name",
				'required' => true,
				'max'      => 155
			],
		];

	public function __construct() {
		$table = 'contacts';
		parent::__construct($table);
		$this->_softDelete = true;
	}

	public function findAllByUserId($user_id, $params) {
		$conditions = [
			'conditions' => 'user_id = ?',
			'bind'       => [$user_id],
		];

		$conditions = array_merge($conditions, $params);
		return $this->find($conditions);
	}

	public function displayName() {
		return $this->fname . ' ' . $this->lname;
	}
}