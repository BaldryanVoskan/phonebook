<?php


namespace App\Model;


use Core\DB;
use Exception;

class Contact
{

    private $_data;
    private $_db;
    private $_isLoggedIn;


    public function __construct()
    {
        $this->_db = DB::getInstance();

    }

    public function update($table, $id = null, $fields = array()) {
        if(!$id && $this->isLoggedIn()){
            $id = $this->data()->id;
        }
        if(!$this->_db->update($table,$id,$fields)) {
            throw new Exception("There was problem updating ");
        }
    }

    public function getContacts($user_id, $offset = 0, $count = 10)
    {
        $data = $this->_db->get('contacts', array('user_id', '=', $user_id), $count, $offset);

        if ($data->count()) {
            return $data->results();
        }
        return $this;

        }

    public function getAllContacts($user_id)
    {
        $data = $this->_db->get('contacts', array('user_id', '=', $user_id));

        if ($data->count()) {
            return $data->results();
        }
            return FALSE;
    }


    public function getContact($contactid){
        $data = $this->_db->get('contacts',array('id','=',$contactid));

              return  $this->_data = $data->first();

    }

    public function data() {
        return $this->_data;
    }

    public function get($table,$where=[]) {
        return $this->_db->get($table,$where);
    }


    public function getone($table,$string) {
        return $this->_db->getone($table,$string);
    }

    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }


    public function delete ($table,$id) {
        if(!$this->_db->deletecontact($table,$id)) {
            throw new Exception("There was problem deleting ");
        }
    }

}