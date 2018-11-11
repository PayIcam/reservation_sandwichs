<?php

class Day {
    public $day_id;
    public $quota;
    public $reservation_opening_date;
    public $reservation_closure_date;
    public $pickup_date;
    public $is_removed;

    public function __construct($day_id) {
        global $db;
        $this->bind($db->queryFirst('SELECT * FROM days WHERE day_id = :day_id', array('day_id' => $day_id)));
    }

    public static function get_all($days_number, $removed_too=true) {
        global $db;
        if($removed_too) {
            $days = $db->query('SELECT * FROM days ORDER BY pickup_date DESC LIMIT 0,:days_number', array("days_number" => $days_number));
        } else {
            $days = $db->query('SELECT * FROM days WHERE is_removed=0 ORDER BY pickup_date DESC LIMIT 0,:days_number', array("days_number" => $days_number));
        }
        return $days;
    }

    public function get_day_sandwiches() {
        global $db;
        return $db->query('SELECT dhs.quota, dhs.sandwich_id, s.name FROM day_has_sandwiches dhs LEFT JOIN sandwiches s ON s.sandwich_id = dhs.sandwich_id WHERE dhs.is_removed=0 and day_id=:day_id', array("day_id" => $this->day_id));
    }

    public static function insert($day, $day_sandwiches) {
        global $db;
        $day_id = $db->query('INSERT INTO days(quota, reservation_opening_date, reservation_closure_date, pickup_date) VALUES (:quota, :reservation_opening_date, :reservation_closure_date, :pickup_date)', array("quota" => $day['quota'], "reservation_opening_date" => $day['reservation_opening_date'], "reservation_closure_date" => $day['reservation_closure_date'], "pickup_date" => $day['pickup_date']));
        foreach($day_sandwiches as $sandwich) {
            $db->query('INSERT INTO day_has_sandwiches(day_id, sandwich_id, quota) VALUES (:day_id, :sandwich_id, :quota)', array("day_id" => $day_id, "sandwich_id" => $sandwich->sandwich_id, "quota" => $sandwich->quota));
        }
        return $day_id;
    }
    public static function update($day, $day_sandwiches) {
        global $db;
        $db->query('UPDATE days SET quota=:quota, reservation_opening_date=:reservation_opening_date, reservation_closure_date=:reservation_closure_date, pickup_date=:pickup_date WHERE day_id=:day_id', array("day_id" => $day['day_id'], "quota" => $day['quota'], "reservation_opening_date" => $day['reservation_opening_date'], "reservation_closure_date" => $day['reservation_closure_date'], "pickup_date" => $day['pickup_date']));

        $previous_sandwichs_ids = array_column($db->query('SELECT sandwich_id FROM day_has_sandwiches WHERE day_id=:day_id', array('day_id' => $day['day_id'])), 'sandwich_id');
        $new_sandwichs_ids = array_column($day_sandwiches, 'sandwich_id');
        $deleted_sandwich_ids = array_diff($previous_sandwichs_ids, $new_sandwichs_ids);

        foreach($day_sandwiches as $sandwich) {
            if(in_array($sandwich->sandwich_id, $previous_sandwichs_ids)) {
                $db->query('UPDATE day_has_sandwiches SET quota=:quota WHERE day_id=:day_id and sandwich_id=:sandwich_id', array("day_id" => $day['day_id'], "sandwich_id" => $sandwich->sandwich_id, "quota" => $sandwich->quota));
            } else {
                $db->query('INSERT INTO day_has_sandwiches(day_id, sandwich_id, quota) VALUES (:day_id, :sandwich_id, :quota)', array("day_id" => $day['day_id'], "sandwich_id" => $sandwich->sandwich_id, "quota" => $sandwich->quota));
            }
        }
        foreach($deleted_sandwich_ids as $sandwich_id) {
            $db->query('UPDATE day_has_sandwiches SET is_removed=1 WHERE day_id=:day_id and sandwich_id=:sandwich_id', array("day_id" => $day['day_id'], "sandwich_id" => $sandwich_id));
        }
        // $db->query('')
    }

    protected function bind($day) {
        $this->day_id = $day['day_id'];
        $this->quota = $day['quota'];
        $this->reservation_opening_date = date('m/d/Y h:i a', strtotime($day['reservation_opening_date']));
        $this->reservation_closure_date = date('m/d/Y h:i a', strtotime($day['reservation_closure_date']));
        $this->pickup_date = date('m/d/Y h:i a', strtotime($day['pickup_date']));
        $this->is_removed = $day['is_removed'];
    }
}

?>