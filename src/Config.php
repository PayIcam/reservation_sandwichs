<?php

class Config {
    public $days_displayed;
    public $default_quota;
    public $default_reservation_closure_time;
    public $default_pickup_time;

    public function __construct() {
        global $db;
        $this->bind($db->queryFirst('SELECT * FROM config'));
    }

    protected function bind($config) {
        $this->days_displayed = $config['days_displayed'];
        $this->default_quota = $config['default_quota'];
        $this->default_reservation_closure_time = $config['default_reservation_closure_time'];
        $this->default_pickup_time = $config['default_pickup_time'];
    }

    public static function update($config) {
        global $db;
        $db->query('UPDATE config SET days_displayed=:days_displayed, default_quota=:default_quota, default_reservation_closure_time=:default_reservation_closure_time, default_pickup_time=:default_pickup_time', array("days_displayed" => $config['days_displayed'], "default_quota" => $config['default_quota'], "default_reservation_closure_time" => $config['default_reservation_closure_time'], "default_pickup_time" => $config['default_pickup_time']));
    }
}

?>