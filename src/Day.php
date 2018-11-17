<?php

class Day {
    public $day_id;
    public $quota;
    public $reservation_opening_date;
    public $reservation_closure_date;
    public $pickup_date;
    public $is_removed;
    public $day;

    public function __construct($day_id) {
        global $db;
        $this->bind($db->queryFirst('SELECT *, date(pickup_date) day FROM days WHERE day_id = :day_id', array('day_id' => $day_id)));
    }

    public static function get_all($days_number, $week_too=false, $removed_too=false) {
        global $db;
        $pdo_db = $db->db;
        if($removed_too && !$week_too) {
            $days = $pdo_db->query("SELECT d.*, date(d.pickup_date) day, SUM(CASE WHEN r.status IN ('V', 'W') THEN 1 ELSE 0 END) current_quota FROM days d LEFT JOIN reservations r ON r.day_id = d.day_id WHERE CURDATE() <= DATE(d.pickup_date) GROUP BY d.day_id, date(d.pickup_date) ORDER BY d.pickup_date LIMIT 0,$days_number");
        } elseif(!$removed_too && !$week_too) {
            $days = $pdo_db->query("SELECT d.*, date(d.pickup_date) day, SUM(CASE WHEN r.status IN ('V', 'W') THEN 1 ELSE 0 END) current_quota FROM days d LEFT JOIN reservations r ON r.day_id = d.day_id WHERE is_removed=0 AND CURDATE() <= DATE(d.pickup_date) GROUP BY d.day_id, date(d.pickup_date) ORDER BY d.pickup_date LIMIT 0,$days_number");
        } elseif(!$removed_too && $week_too) {
            $days = $pdo_db->query("SELECT d.*, date(d.pickup_date) day, SUM(CASE WHEN r.status IN ('V', 'W') THEN 1 ELSE 0 END) current_quota FROM days d LEFT JOIN reservations r ON r.day_id = d.day_id WHERE is_removed=0 AND (CURDATE() <= DATE(d.pickup_date) OR (WEEK(CURDATE()) = WEEK(d.pickup_date) AND YEAR(CURDATE()) = YEAR(d.pickup_date) GROUP BY d.day_id, date(d.pickup_date) ORDER BY d.pickup_date LIMIT 0,$days_number");
        } else {
            $days = $pdo_db->query("SELECT d.*, date(d.pickup_date) day, SUM(CASE WHEN r.status IN ('V', 'W') THEN 1 ELSE 0 END) current_quota FROM days d LEFT JOIN reservations r ON r.day_id = d.day_id WHERE CURDATE() <= DATE(d.pickup_date) OR (WEEK(CURDATE()) = WEEK(d.pickup_date) AND YEAR(CURDATE()) = YEAR(d.pickup_date) GROUP BY d.day_id, date(d.pickup_date) ORDER BY d.pickup_date LIMIT 0,$days_number");
        }
        $days = $days->fetchAll();

        foreach($days as &$day) {
            $sandwiches = $db->query('SELECT dhs.quota, dhs.sandwich_id, s.name, SUM(CASE WHEN r.status IN ("V" , "W") THEN 1 ELSE 0 END) current_quota FROM day_has_sandwiches dhs LEFT JOIN sandwiches s ON s.sandwich_id = dhs.sandwich_id LEFT JOIN reservations r ON r.sandwich_id = dhs.sandwich_id WHERE dhs.is_removed=0 and dhs.day_id=:day_id GROUP BY dhs.quota, dhs.sandwich_id, s.name', array("day_id" => $day['day_id']));
            $reservation = $db->queryFirst('SELECT * FROM reservations WHERE day_id=:day_id and email=:email and status != "A"', array("day_id" => $day['day_id'], "email" => $_SESSION['icam_informations']->mail));

            $day['sandwiches'] = $sandwiches;
            $day['reservation'] = $reservation;
        }

        return $days;
    }

    public function get_day_sandwiches() {
        global $db;
        return $db->query('SELECT dhs.quota, s.sandwich_id, s.name, dhs.is_removed FROM day_has_sandwiches dhs LEFT JOIN sandwiches s ON s.sandwich_id = dhs.sandwich_id WHERE day_id=:day_id UNION SELECT default_quota quota, sandwich_id, name, 1 FROM sandwiches WHERE sandwich_id NOT IN (SELECT sandwich_id FROM day_has_sandwiches WHERE day_id=:day_id)', array("day_id" => $this->day_id));
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
    }

    public static function can_book_sandwiches($day) {
        return strtotime($day['reservation_closure_date']) - time() >0 && $day['current_quota'] < $day['quota'];
    }

    public static function can_cancel_reservation($day) {
        return strtotime($day['reservation_closure_date']) - time() > 0 ;
    }

    public static function already_created($pickup_date, $reservation_id=false) {
        global $db;
        if($reservation_id===false) {
            return $db->queryFirst('SELECT CASE WHEN DATE(:pickup_date) IN (SELECT DATE(pickup_date) FROM days) THEN 1 ELSE 0 END already_created', array('pickup_date' => $pickup_date))['already_created'];
        } else {
            return $db->queryFirst('SELECT CASE WHEN DATE(:pickup_date) IN (SELECT DATE(pickup_date) FROM days where reservation_id != :reservation_id) THEN 1 ELSE 0 END already_created', array('pickup_date' => $pickup_date, 'reservation_id' => $reservation_id))['already_created'];
        }
    }

    protected function bind($day) {
        $this->day_id = $day['day_id'];
        $this->quota = $day['quota'];
        $this->reservation_opening_date = date('m/d/Y h:i a', strtotime($day['reservation_opening_date']));
        $this->reservation_closure_date = date('m/d/Y h:i a', strtotime($day['reservation_closure_date']));
        $this->pickup_date = date('m/d/Y h:i a', strtotime($day['pickup_date']));
        $this->is_removed = $day['is_removed'];
        $this->day = $day['day'];
    }

    public static function display_action_button($day, $possibilities) {
        if(empty($day['reservation'])) {
            if(!self::can_book_sandwiches($day)) { ?>
                <button type="button" class="btn btn-primary button_disabled" disabled title='Quota déjà rempli ou date passée'>Réserver un sandwich</button>
            <?php } else { ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$day['day_id']?>">
                    Réserver un sandwich
                </button>
                <?php self::display_modal($day, $possibilities);
            }
        } else {
            if($day['reservation']['status'] == 'W') { ?>
                <a href="<?=$day['reservation']['payicam_transaction_url']?>" type="button" class="btn btn-primary">
                    Payer la réservation
                </button>
                <a href="processing/cancel_reservation.php?reservation_id=<?=$day['reservation']['reservation_id']?>" type="button" class="btn btn-danger cancel_reservation"> Annuler la réservation </button>
            <?php } elseif(self::can_cancel_reservation($day)) { ?>
                <button data-reservation_id="<?=$day['reservation']['reservation_id']?>" type="button" class="btn btn-danger cancel_reservation"> Annuler la réservation </button>
            <?php } else { ?>
                <button type="button" class="btn btn-danger button_disabled" disabled title='Trop tard pour annuler'>Annuler la réservation</button>
            <?php }
        }
    }

    public static function display_modal($day, $possibilities) { ?>

        <div class="modal fade" id="modal<?=$day['day_id']?>" data-day_id="<?=$day['day_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?=$day['day']?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th width="30%" scope="col">Sandwichs disponibles</th>
                                    <?php foreach($possibilities as $possibility) { ?>
                                        <th scope="col"><?=$possibility['name']?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($day['sandwiches'] as $sandwich) {
                                Sandwich::display_reservation_table_row($sandwich, $possibilities);
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php }
}

?>