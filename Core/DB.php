<?php

namespace Core;

use Core\Config;
use PDO;
use PDOException;

class DB
{
    private static $_instance = null;
    private $_pdo,
        $_query,
        $_error = false,
        $_results,
        $_count = 0;

    public function __construct()
    {

        try {
            $this->create('myphonebook');
            $this->create_table('users', 'myphonebook');

            $dsn = 'mysql:dbname=myphonebook;host=127.0.0.1';
            $user = 'root';
            $password = '';
            $this->_pdo = new PDO($dsn, $user, $password);

        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function create($a)
    {

    }

    public function create_table($table, $name_db)
    {


        try {
            $db = new PDO("mysql:dbname=$name_db;host=localhost", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Error Handling
            $sql = "CREATE TABLE IF NOT EXISTS $table(
                         id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                         username VARCHAR( 150 ) NOT NULL, 
                         fullname VARCHAR( 250 ) NOT NULL,
                         email VARCHAR( 255 ) NOT NULL, 
                         password VARCHAR( 150 ) NOT NULL, 
                         salt VARCHAR( 150 ) NOT NULL) CHARACTER SET utf8 COLLATE utf8_general_ci;";
            $db->exec($sql);
        } catch (PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql, $params = array())
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    public function  action($action, $table, $where = [], $offset = 0,$count=10)
    {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where['1'];
            $value = $where['2'];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?  LIMIT {$count} OFFSET {$offset}";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    public function  actionAll($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where['1'];
            $value = $where['2'];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function get($table, $where, $offset = 0,$count = 10)
    {
        return $this->action('SELECT *', $table, $where, $offset,$count);
    }
    public function getAll($table, $where)
    {
        return $this->actionAll('SELECT *', $table, $where);
    }

    public function getone($table,$string) {
        return $this->action('SELECT '. $string.'FROM', $table);
    }

    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

    public function results()
    {
        return $this->_results;
    }

    public function first()
    {
        if(!empty($this->results())){
            return $this->results()[0];
        }else {
            return null;
        }

    }

    public function insert($table, $fields = array())
    {
        $keys = array_keys($fields);
        $values = '';
        $x = 1;
        foreach ($fields as $field) {
            $values .= '?';

            if ($x < count($fields)) {
                $values .= ', ';
            }
            $x++;
        }
        $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        } else {



        }
        return false;
    }

    public function update($table, $id, $fields)
    {
        $set = '';
        $x = 1;
        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        echo $sql;
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function error()
    {
        return $this->_error;
    }


    public function count()
    {
        return $this->_count;
    }

    public function deletecontact($table, $id)
    {

        $sql = " DELETE FROM {$table}  WHERE id = {$id}";
        echo $sql;
        if (!$this->query($sql)->error()) {
            return true;
        }
        return false;
    }

}


