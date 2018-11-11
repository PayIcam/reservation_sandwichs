<?php

class Sandwich {
    public $sandwich_id;
    public $name;
    public $default_quota;
    public $description;
    public $is_removed;

    public function __construct($sandwich_id) {
        global $db;
        $this->bind($db->queryFirst('SELECT * FROM sandwiches WHERE sandwich_id = :sandwich_id', array('sandwich_id' => $sandwich_id)));
    }

    public static function get_all($removed_too=true) {
        global $db;
        if($removed_too) {
            $sandwiches = $db->query('SELECT * FROM sandwiches');
        } else {
            $sandwiches = $db->query('SELECT * FROM sandwiches WHERE is_removed=0');
        }
        return $sandwiches;
    }

    public static function insert($sandwich) {
        global $db;
        $db->query('INSERT INTO sandwiches(name, default_quota, description) VALUES (:name, :default_quota, :description)', array("name" => $sandwich['name'], "default_quota" => $sandwich['default_quota'], "description" => $sandwich['description']));
    }
    public static function update($sandwich) {
        global $db;
        $db->query('UPDATE sandwiches SET name=:name, default_quota=:default_quota, description=:description WHERE sandwich_id=:sandwich_id', array("sandwich_id" => $sandwich['sandwich_id'], "name" => $sandwich['name'], "default_quota" => $sandwich['default_quota'], "description" => $sandwich['description']));
    }

    protected function bind($sandwich) {
        $this->sandwich_id = $sandwich['sandwich_id'];
        $this->name = $sandwich['name'];
        $this->default_quota = $sandwich['default_quota'];
        $this->description = $sandwich['description'];
        $this->is_removed = $sandwich['is_removed'];
    }
}

?>