<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 */

namespace Core;

use \PDO;
use \PDOException;

class DB
{
	/**
	 * @var PDO $_pdo - stores PDO connection to database.
	 */
	private $_pdo;

	/**
	 * @var PDOStatement|bool _query - stores PDO statement instance
	 */
	private $_query;

	/**
	 * @var bool $_error - true if query has been executed with errors.
	 */
	private $_error = false;

	/**
	 * @var string $_errorInfo - stores errors messages if query has been executed with errors.
	 */
	private $_errorInfo = '';

	/**
	 * @var array <b>PDOStatement::fetchAll</b> _result - store fetchAll() result.
	 */
	private $_result;

	/**
	 * @var int $_count - count rows of query result.
	 */
	private $_count = 0;

	/**
	 * @var int|null _lastInsertID - stores last insertion id or null if faliled.
	 */
	private $_lastInsertID = null;

	/**
	 * @var bool _debugMode - stores state of debug mode: true - on, false - off
	 */
	private $debugMode = false;

	/**
	 * @var null| self _instance - stores self instance
	 */
	private static $_instance = null;

	private function __construct()
	{
		try {
			$this->_pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
			$setSQL     = 'SET NAMES utf8 COLLATE utf8_general_ci';
			$this->_pdo->query($setSQL);

			if ($this->debugMode) {
				$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			}

		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * Singleton method to get DB instance.
	 * @return DB|null
	 */
	public static function getInstance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**Shows MySQL query with bound params and stops script.
	 * Sets debug mode to true, if passed any true value as a parameter.
	 * To switch ON debug mode call
	 * <pre>
	 * $db->setDebugMode(1);
	 * </pre>
	 * after DB::getInstance();
	 *
	 * @param bool $on
	 */
	public function setDebugMode($on = false)
	{
		/* To switch ON debug mode call
		 $db->setDebugMode(1);
		 before use any method
		*/
		if ($on == true) {
			$this->debugMode = true;
		}
	}


	/**
	 * Executes SQL with given parameters, set _errors if exist, set _count and _lastInsertID, and returns DB instance.
	 * @param string $sql - parameterized SQL query
	 * @param array $params - array of parameters to bind
	 * @param string|bool $class - if set to string, fetches (PDO::FETCH_CLASS, $class).  By default - PDO::FETCH_OBJ.
	 *
	 * @return $this
	 */
	public function query($sql, $params = [], $class = false)
	{
		$this->_error = false;

		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if (is_array($params) && count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
		}

		//show SQL statement, if debug mode ON
		if ($this->debugMode === true) {
			var_dump($sql);
			var_dump(DB::interpolateQuery($sql, $params));
			die();
		}

		if ($this->_query->execute()) {
			if ($class) {
				$this->_result = $this->_query->fetchAll(PDO::FETCH_CLASS, $class);
			}
			else {
				$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
			}
			$this->_count        = $this->_query->rowCount();
			$this->_lastInsertID = $this->_pdo->lastInsertId();
		}
		else {
			$this->_error     = true;
			$this->_errorInfo = $this->_query->errorInfo();
		}
		return $this;
	}

	/**
	 * Replaces any parameter placeholders in a query with the value of that parameter. Useful for debugging.
	 * Assumes anonymous parameters from $params are in the same order as specified in $query
	 *
	 * @param string $query - The sql query with parameter placeholders
	 * @param array $params - The array of substitution parameters
	 *
	 * @return string The interpolated query
	 */
	public static function interpolateQuery($query, $params)
	{
		$keys = array();

		# build a regular expression for each parameter
		foreach ($params as $key => $value) {
			if (is_string($key)) {
				$keys[] = '/:' . $key . '/';
			}
			else {
				$keys[] = '/[?]/';
			}
		}

		$query = preg_replace($keys, $params, $query, 1, $count);

		return $query;
	}

	/** Calls SELECT query for specified table.
	 * @param string $table - table name
	 * @param array $params - array with optional elements: 'conditions', 'bind', 'limit', 'order'
	 * @param string|bool $class - fetch as class if string given, as object by default
	 *
	 * @return bool - whether any records selected or not.
	 */
	protected function _read($table, $params = [], $class = false)
	{
		$conditionString = '';

		$bind  = [];
		$order = '';
		$limit = '';

		//conditions
		if (isset($params['conditions'])) {
			if (is_array($params['conditions'])
				&& !empty($params['conditions'])
			) {
				foreach ($params['conditions'] as $condition) {
					$conditionString .= ' ' . $condition . ' AND ';
				}
				$conditionString = trim($conditionString);
				$conditionString = rtrim($conditionString, ' AND');

			}
			else {
				$conditionString = $params['conditions'];
			}
			if ($conditionString != '') {
				$conditionString = 'WHERE ' . $conditionString;
			}
		}

		//bind
		if (array_key_exists('bind', $params)) {
			$bind = $params['bind'];
		}

		//order
		if (array_key_exists('order', $params)) {
			$order = ' ORDER BY ' . $params['order'];
		}

		//limit
		if (array_key_exists('limit', $params)) {
			$limit = ' LIMIT ' . $params['limit'];
		}

		$sql = "SELECT * FROM {$table} {$conditionString}{$order}{$limit}";

		if ($this->query($sql, $bind, $class)) {
			return (bool)$this->count();
		}
		return false;

	}

	/** Runs SELECT query for specified table from outside of DB class.
	 * @see DB::_read() method.
	 * @param string $table - table name
	 * @param array $params - array with optional elements: 'conditions', 'bind', 'limit', 'order'
	 * @param string|bool $class - fetch as class if string given, as object by default
	 * @return bool|array <b>PDOStatement::fetchAll</b> - records selected or false if no such records.
	 */
	public function find($table, $params = [], $class = false)
	{
		if ($this->_read($table, $params, $class)) {
			return $this->getResults();
		}
		return false;
	}

	/** Returns first result of DB::_read() or false if no results found.
	 * @see DB:_read() method.
	 * @see DB::find() method.
	 * @param string $table
	 * @param array $params
	 * @param string|bool $class
	 *
	 * @return bool|mixed
	 */
	public function findFirst($table, $params = [], $class = false)
	{
		if ($this->_read($table, $params, $class)) {
			return $this->first();
		}
		return false;
	}

	/** Runs INSERT query for specified table.
	 * Given field names and values should be scalar values.
	 * @param string $table - table name
	 * @param array $fields - [['field_name1' => 'value']... ['field_name_n' => 'value']]
	 *
	 * @return bool - true on success, false on failure
	 */
	public function insert($table, $fields = [])
	{
		$fieldString = '';
		$valueString = '';
		$values      = [];
		foreach ($fields as $field => $value) {
			$fieldString .= '`' . $field . '`,';
			$valueString .= '?,';
			$values[]    = $value;
		}

		$fieldString = rtrim($fieldString, ',');
		$valueString = rtrim($valueString, ',');

		$sql = "INSERT INTO " . $table . "
                  (" . $fieldString . ")
                    VALUES
                  (" . $valueString . ")";

		if (!$this->query($sql, $values)->error()) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Runs UPDATE query for specified table.
	 * Given field names and values should be scalar values.
	 * @param string $table - table name
	 * @param integer $id - record id to be updated
	 * @param array $fields - [['field_name1' => 'value']... ['field_name_n' => 'value']]
	 *
	 * @return bool - true on success, false on failure
	 */
	public function update($table, $id, $fields = [])
	{
		$fieldString = '';
		$values      = [];
		if (is_array($fields) && count($fields)) {
			foreach ($fields as $field => $value) {
				if (!empty($value)) {
					$fieldString .= ' ' . $field . ' =?,';
					$values[]    = $value;
				}
			}
		}

		if (empty($values)) {
			return false;
		}
		$fieldString = trim($fieldString);
		$fieldString = rtrim($fieldString, ',');


		$sql = "UPDATE {$table} SET {$fieldString} WHERE id = {$id}";

		if (!$this->query($sql, $values)->error()) {
			return true;
		}
		return false;
	}

	/**
	 * Runs DELETE query for specified table.
	 * @param string $table - table name
	 * @param integer $id - record id to de deleted
	 *
	 * @return bool - true on success, false on failure
	 */
	public function delete($table, $id)
	{
		if (is_numeric($id) && (int)$id > 0) {
			$id = (int)$id;
		}
		else {
			return false;
		}

		$sql = "DELETE FROM {$table} WHERE id = {$id}";

		if (!$this->query($sql)->error()) {
			return true;
		}
		return false;
	}

	/**
	 * Getter for _result.
	 * @return array <b>PDOStatement::fetchAll</b>
	 */
	public function getResults()
	{
		return $this->_result;
	}

	/** Returns first element of _result or empty array.
	 * @return array|mixed
	 */
	public function first()
	{
		return (!empty($this->_result)) ? $this->_result[0] : [];
	}

	/**
	 * Getter for count.
	 * @return int
	 */
	public function count()
	{
		return $this->_count;
	}

	/**
	 * Getter for _lastInsertID.
	 * @return int|null
	 */
	public function lastID()
	{
		return $this->_lastInsertID;
	}

	/**
	 * Retuns columns for given table.
	 * @param string $table - table name
	 *
	 * @return array
	 */
	public function get_columns($table)
	{
		return $this->query("SHOW COLUMNS FROM {$table}")->getResults();
	}

	/** Getter for error.
	 * @return bool
	 */
	public function error()
	{
		return $this->_error;
	}

	/**
	 * Getter for errorInfo.
	 * @return string
	 */
	public function getErrorInfo()
	{
		return $this->_errorInfo;
	}
}