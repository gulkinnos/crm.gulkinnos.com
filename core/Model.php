<?php


class Model
{
	protected $_db;
	protected $_table;
	protected $_modelName;
	protected $_softDelete  = false;

	public $id;

	public function __construct($table) {
		$this->_db    = DB::getInstance();
		$this->_table = $table;

		// 'table_name' >>> 'TableName'
		$this->_modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table))
		);

	}

	public function getColumns() {
		return $this->_db->get_columns($this->_table);
	}

	/**
	 *
	 * @param $params array
	 * @return array
	 */
	protected function _softDeleteParams($params)
	{
		if ($this->_softDelete) {
			if (array_key_exists('conditions', $params)) {
				if (!is_array($params)) {
					$params['conditions'][] = " (deleted != 1 or deleted IS NULL)";
				}
				else {
					$params['conditions'] .= ' AND (deleted != 1 or deleted IS NULL)';
				}
			}
			else {
				$params['conditions'] = " (deleted != 1 or deleted IS NULL)";
			}
		}
		return $params;
	}

	public function find($params = []) {
		$params       = $this->_softDeleteParams($params);
		$resultsQuery = $this->_db->find($this->_table, $params, get_class($this));
		if(is_array($resultsQuery) && !empty($resultsQuery)) {
			return $resultsQuery;
		}
		else {
			return [];
		}
	}

	public function findFirst($params = []) {
		$params = $this->_softDeleteParams($params);
		return $this->_db->findFirst($this->_table, $params, get_class($this));
	}

	public function findById($id) {
		return $this->findFirst(['conditions' => 'id = ?', 'bind' => [$id]]);
	}


	public function save() {
		$fields = Helpers::getObjectProperties($this);

		if(array_key_exists('deleted',$fields) && is_null($fields['deleted'])){
			$fields['deleted'] = 0;
		}

		// Determine whether to update or insert
		if(property_exists($this, 'id') && !empty($this->id)) {
			return $this->update($this->id, $fields);
		}
		else {
			return $this->insert($fields);
		}
	}

	public function insert($fields = []) {
		if(empty($fields)) {
			return false;
		}
		return $this->_db->insert($this->_table, $fields);
	}

	public function update($id, $fields = []) {
		if(empty($fields) || !is_numeric($id) || empty($id)) {
			return false;
		}
		return $this->_db->update($this->_table, $id, $fields);
	}

	public function delete($id = 0) {

		$isEmptyId     = !is_numeric($id) || empty($id);
		$isEmptyThisId = !is_numeric($this->id) || empty($this->id);

		if($isEmptyId && $isEmptyThisId) {
			return false;
		}

		$id = ($isEmptyId) ? $this->id : $id;

		if($this->_softDelete) {
			return $this->update($id, ['deleted' => 1]);
		}

		return $this->_db->delete($this->_table, $id);
	}

	public function query($sql, $bind = []) {
		return $this->_db->query($sql, $bind);
	}

	public function data() {
		$data = new DataModel();
		foreach (Helpers::getObjectProperties($this) as $column => $value) {

			//not sure. maybe $data->$column???
			$data->$column = $value;
		}

		return $data;
	}

	public function assign($params) {
		if(!empty($params)) {
			foreach ($params as $key => $value) {
				if(property_exists($this, $key)) {
					$this->$key = FormHelpers::sanitize($value);
				}
			}
			return true;
		}
		return false;
	}


	protected function populateObjData($result) {
		if(is_object($result) && !empty($result)) {
			foreach ($result as $key => $value) {
				$this->$key = $value;
			}
		}
	}

}