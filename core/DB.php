<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 5/23/2018
 * Time: 4:29 PM
 */

class DB
{
    private static $_instance = null;

    private $_pdo, $_query, $_error = false, $_result, $_count = 0, $_lastInsertID = null;

    private $debugMode = false;

    /**
     * DB constructor.
     */
    private function __construct()
    {

        try {
            $this->_pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    /**
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
     * @param $sql
     * @param array $params
     * @return $this
     */
    public function query($sql, $params = [])
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
            $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
            $this->_lastInsertID = $this->_pdo->lastInsertId();
        } else {
            $this->_error = true;
        }
        return $this;
    }

    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query The sql query with parameter placeholders
     * @param array $params The array of substitution parameters
     * @return string The interpolated query
     */
    public static function interpolateQuery($query, $params)
    {
        $keys = array();

        # build a regular expression for each parameter
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:' . $key . '/';
            } else {
                $keys[] = '/[?]/';
            }
        }

        $query = preg_replace($keys, $params, $query, 1, $count);

        #trigger_error('replaced '.$count.' keys');

        return $query;
    }

    /**
     * @param $table
     * @param array $params
     * @return bool
     */
    protected function _read($table, $params = [])
    {
        $conditionString = '';
        $bind = [];
        $order = '';
        $limit = '';

        //conditions

        if (isset($params['conditions'])) {
            if (is_array($params['conditions']) && !empty($params['conditions'])) {
                foreach ($params['conditions'] as $condition) {
                    $conditionString .= ' ' . $condition . ' AND ';
                }
                $conditionString = trim($conditionString);
                $conditionString = rtrim($conditionString, ' AND');

            } else {
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

        if ($this->query($sql, $bind)) {
            if (!$this->count($this->_result)) {
                return false;
            } else {
                return true;
            }

        }
        return false;

    }

    public function find($table, $params = [])
    {
        if ($this->_read($table, $params)) {
            return $this->results();
        }
        return false;
    }

    public function findFirst($table, $params = [])
    {
        if ($this->_read($table, $params)) {
            return $this->first();
        }
        return false;
    }

    /**
     * @param $table
     * @param array $fields
     * @return bool
     */
    public function insert($table, $fields = [])
    {
        $fieldString = '';
        $valueString = '';
        $values = [];
        foreach ($fields as $field => $value) {
            $fieldString .= '`' . $field . '`,';
            $valueString .= '?,';
            $values[] = $value;
        }

        $fieldString = rtrim($fieldString, ',');
        $valueString = rtrim($valueString, ',');

        $sql = "INSERT INTO " . $table . "
                  (" . $fieldString . ")
                    VALUES
                  (" . $valueString . ")";

        if (!$this->query($sql, $values)->error()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $table
     * @param $id
     * @param array $fields
     * @return bool
     */
    public function update($table, $id, $fields = [])
    {
        $fieldString = '';
        $values = [];
        if (is_array($fields) && count($fields)) {
            foreach ($fields as $field => $value) {
                if (!empty($value)) {
                    $fieldString .= ' ' . $field . ' =?,';
                    $values[] = $value;
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
     * @param $table
     * @param $id
     * @return bool
     */
    public function delete($table, $id)
    {
        if (is_numeric($id) && (int)$id > 0) {
            $id = (int)$id;
        } else {
            return false;
        }

        $sql = "DELETE FROM {$table} WHERE id = {$id}";

        if (!$this->query($sql)->error()) {
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function results()
    {
        return $this->_result;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return (!empty($this->_result)) ? $this->_result[0] : [];
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * @return mixed
     */
    public function lastID()
    {
        return $this->_lastInsertID;
    }

    /**
     * @param $table
     * @return mixed
     */
    public function get_columns($table)
    {
        return $this->query("SHOW COLUMNS FROM {$table}")->results();
    }

    /**
     * @return bool
     */
    public function error()
    {
        return $this->_error;
    }
}