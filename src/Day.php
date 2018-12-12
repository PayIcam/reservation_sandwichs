<?php

class Day {
    public $day_id;
    public $quota;
    public $reservation_opening_date;
    public $reservation_first_closure_date;
    public $reservation_second_closure_date;
    public $pickup_date;
    public $is_removed;
    public $day;
    public $current_quota;

    public function __construct($day_id) {
        global $db;
        $this->bind($db->queryFirst('SELECT d.*, date(d.pickup_date) day, COUNT(r.reservation_id) current_quota FROM days d LEFT JOIN reservations r ON r.day_id=d.day_id WHERE d.day_id = :day_id and r.status IN ("V", "W")', array('day_id' => $day_id)));
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
            $sandwiches = $db->query('SELECT dhs.quota, dhs.sandwich_id, s.name, s.description, SUM(CASE WHEN r.status IN ("V" , "W") THEN 1 ELSE 0 END) current_quota, is_special, closure_type FROM day_has_sandwiches dhs LEFT JOIN sandwiches s ON s.sandwich_id = dhs.sandwich_id LEFT JOIN reservations r ON r.sandwich_id = dhs.sandwich_id and r.day_id=dhs.day_id WHERE dhs.is_removed=0 and dhs.day_id=:day_id GROUP BY dhs.quota, dhs.sandwich_id, s.sandwich_id', array("day_id" => $day['day_id']));
            $reservation = $db->queryFirst('SELECT * FROM reservations WHERE day_id=:day_id and email=:email and status != "A"', array("day_id" => $day['day_id'], "email" => $_SESSION['icam_informations']->mail));

            $day['sandwiches'] = $sandwiches;
            $day['reservation'] = $reservation;
        }

        return $days;
    }

    public function get_day_sandwiches() {
        global $db;
        return $db->query('SELECT dhs.quota, s.sandwich_id, s.name, dhs.is_removed, s.is_special, s.closure_type FROM day_has_sandwiches dhs LEFT JOIN sandwiches s ON s.sandwich_id = dhs.sandwich_id WHERE day_id=:day_id UNION SELECT default_quota quota, sandwich_id, name, 1, is_special, closure_type FROM sandwiches WHERE sandwich_id NOT IN (SELECT sandwich_id FROM day_has_sandwiches WHERE day_id=:day_id)', array("day_id" => $this->day_id));
    }
    public function get_sandwiches_quota() {
        global $db;
        return $db->query("SELECT dhs.quota, s.sandwich_id, s.name, s.closure_type, s.is_special, SUM(CASE WHEN r.status IN ('V', 'W') THEN 1 ELSE 0 END) current_quota FROM day_has_sandwiches dhs LEFT JOIN reservations r ON r.sandwich_id=dhs.sandwich_id and r.day_id=dhs.day_id LEFT JOIN sandwiches s ON s.sandwich_id = dhs.sandwich_id WHERE dhs.day_id=:day_id and dhs.is_removed=0 GROUP BY s.sandwich_id, dhs.quota", array("day_id" => $this->day_id));
    }
    public static function get_sandwich_day_stats($ids) {
        $demi_ids_query = str_repeat ('?, ',  count ($ids['demi_ids']) - 1) . '?';
        $data = array_merge($ids['demi_ids'], $ids['demi_ids']);
        array_push($data, $ids['day_id']*1);
        global $db;
        return $db->query("SELECT s.name, SUM(CASE WHEN r.possibility_id IN ($demi_ids_query) THEN 1 ELSE 0 END) demis, SUM(CASE WHEN r.possibility_id NOT IN ($demi_ids_query) THEN 1 ELSE 0 END) baguettes FROM `reservations` r LEFT JOIN sandwiches s ON s.sandwich_id=r.sandwich_id WHERE day_id=? and status ='V' GROUP BY s.sandwich_id", $data);
    }

    public static function insert($day, $day_sandwiches) {
        global $db;
        $day_id = $db->query('INSERT INTO days(quota, reservation_opening_date, reservation_first_closure_date, reservation_second_closure_date, pickup_date) VALUES (:quota, :reservation_opening_date, :reservation_first_closure_date, :reservation_second_closure_date, :pickup_date)', $day);
        foreach($day_sandwiches as $sandwich) {
            $db->query('INSERT INTO day_has_sandwiches(day_id, sandwich_id, quota) VALUES (:day_id, :sandwich_id, :quota)', array("day_id" => $day_id, "sandwich_id" => $sandwich->sandwich_id, "quota" => $sandwich->quota));
        }
        return $day_id;
    }
    public static function update($day, $day_sandwiches) {
        global $db;
        $db->query('UPDATE days SET quota=:quota, reservation_opening_date=:reservation_opening_date, reservation_first_closure_date=:reservation_first_closure_date, reservation_second_closure_date=:reservation_second_closure_date, pickup_date=:pickup_date WHERE day_id=:day_id', $day);

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

    public static function can_book_sandwiches($day, $classic=true) {
        if($classic) {
            return strtotime($day['reservation_first_closure_date']) - time() >0 && $day['current_quota'] < $day['quota'];
        } else {
            return strtotime($day['reservation_second_closure_date']) - time() >0 && $day['current_quota'] < $day['quota'];
        }
    }
    public function can_book($classic=true) {
        if($classic) {
            return strtotime($this->reservation_first_closure_date) - time() >0 && $this->current_quota < $this->quota;
        } else {
            return strtotime($this->reservation_second_closure_date) - time() >0 && $this->current_quota < $this->quota;
        }
    }
    public function can_book_possibility($closure_type) {
        if($closure_type==0) {
            return strtotime($this->reservation_second_closure_date) - time() >0;
        } else {
            return strtotime($this->reservation_first_closure_date) - time() >0;
        }
    }
    public function can_book_sandwich($sandwich) {
        if($sandwich['current_quota'] < $sandwich['quota']) {
            if($sandwich['closure_type']==0) {
                return strtotime($this->reservation_second_closure_date) - time() >0;
            } else {
                return strtotime($this->reservation_first_closure_date) - time() >0;
            }
        } else {
            return false;
        }
    }

    public static function closure_is_passed($day) {
        return time() - strtotime($day['reservation_second_closure_date'])> 0 ;
    }
    public static function closure_is_passed_reservation_id($reservation_id) {
        global $db;
        $closure_date = $db->queryFirst('SELECT reservation_second_closure_date FROM days d LEFT JOIN reservations r ON r.day_id=d.day_id WHERE reservation_id=:reservation_id', array('reservation_id' => $reservation_id))['reservation_second_closure_date'];
        return time() - strtotime($closure_date)> 0 ;
    }

    public static function already_created($pickup_date, $day_id=false) {
        global $db;
        if($day_id===false) {
            return $db->queryFirst('SELECT CASE WHEN DATE(:pickup_date) IN (SELECT DATE(pickup_date) FROM days) THEN 1 ELSE 0 END already_created', array('pickup_date' => $pickup_date))['already_created'];
        } else {
            return $db->queryFirst('SELECT CASE WHEN DATE(:pickup_date) IN (SELECT DATE(pickup_date) FROM days where day_id != :day_id) THEN 1 ELSE 0 END already_created', array('pickup_date' => $pickup_date, 'day_id' => $day_id))['already_created'];
        }
    }
    public static function cant_change_day($pickup_date, $day_id) {
        global $db;
            return $db->queryFirst('SELECT CASE WHEN DATE(:pickup_date)!=DATE(pickup_date) AND (SELECT COUNT(*) FROM reservations where day_id = :day_id and status IN("V", "W"))>0 THEN 1 ELSE 0 END cant_change FROM days WHERE day_id=:day_id', array('pickup_date' => $pickup_date, 'day_id' => $day_id))['cant_change'];
    }

    public function export_reservations_csv($day_id, $fields=['firstname','lastname','email','promo','payement','status','reservation_date','payment_date','pickup_date','possibility','sandwich']) {
        $reservations = Reservation::get_all($this->day_id, 'V');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$this->day.".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo 'Compteur,';
        foreach($reservations[0] as $key => $value) {
            if(in_array($key, $fields)) {
                echo $key . ',';
            }
        }
        echo "\n";

        $i=1;
        foreach($reservations as $reservation) {
            echo $i . ",";
            foreach($reservation as $key => $value) {
                if(in_array($key, $fields)) {
                    if($value === null) {
                        echo ' ';
                    }
                    echo $value . ',';
                }
            }
            echo "\n";
            $i++;
        }
        exit();
    }

    protected function bind($day) {
        $this->day_id = $day['day_id'];
        $this->quota = $day['quota'];
        $this->reservation_opening_date = date('m/d/Y h:i a', strtotime($day['reservation_opening_date']));
        $this->reservation_first_closure_date = date('m/d/Y h:i a', strtotime($day['reservation_first_closure_date']));
        $this->reservation_second_closure_date = date('m/d/Y h:i a', strtotime($day['reservation_second_closure_date']));
        $this->pickup_date = date('m/d/Y h:i a', strtotime($day['pickup_date']));
        $this->is_removed = $day['is_removed'];
        $this->day = $day['day'];
        $this->current_quota = $day['current_quota'];
    }

    public static function display_action_buttons($day, $possibilities) {
        if(empty($day['reservation'])) {
            if(self::can_book_sandwiches($day)) { ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$day['day_id']?>0">
                    Réserver un classique
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$day['day_id']?>1">
                    Réserver un spécial
                </button>
                <?php self::display_modal($day, $possibilities['classics'], 0);
                self::display_modal($day, $possibilities['specials'], 1);
            } elseif(self::can_book_sandwiches($day, false)) { ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$day['day_id']?>0">
                    Réserver un classique
                </button>
                <button type="button" class="btn btn-primary button_disabled" disabled title='Quota déjà rempli ou date passée'>Réserver un spécial</button>
                <?php self::display_modal($day, $possibilities['classics']);
            } else { ?>
                <button type="button" class="btn btn-primary button_disabled" disabled title='Quota déjà rempli ou date passée'>Réserver un classique</button>
                <button type="button" class="btn btn-primary button_disabled" disabled title='Quota déjà rempli ou date passée'>Réserver un spécial</button>
            <?php }
        } else {
            if($day['reservation']['status'] == 'W') { ?>
                <a href="<?=$day['reservation']['payicam_transaction_url']?>" type="button" class="btn btn-primary">
                    Payer la réservation
                </button>
                <a href="processing/cancel_reservation.php?reservation_id=<?=$day['reservation']['reservation_id']?>&status=W" type="button" class="btn btn-danger cancel_reservation"> Annuler la réservation </button>
            <?php } elseif(!self::closure_is_passed($day) && $day['reservation']['payement'] == 'PayIcam') { ?>
                <a href="processing/cancel_reservation.php?reservation_id=<?=$day['reservation']['reservation_id']?>&status=V" type="button" class="btn btn-danger cancel_reservation"> Annuler la réservation </button>
            <?php } elseif($day['reservation']['payement'] != 'PayIcam') { ?>
                <button class="btn btn-danger button_disabled" disabled title="Impossible d'annuler un sandwich ajouté à la main">Annuler la réservation</button>
            <?php } else { ?>
                <button class="btn btn-danger button_disabled" disabled title='Trop tard pour annuler'>Annuler la réservation</button>
            <?php }
        }
    }

    public static function display_modal($day, $possibilities) {
        $is_special = $possibilities[0]['is_special'];?>

        <div class="modal fade" id="modal<?=$day['day_id'] . $is_special?>" data-day_id="<?=$day['day_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel<?=$is_special?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel<?=$is_special?>"><?=$day['day']?></h5>
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
                                        <th scope="col"><?php
                                            echo $possibility['name'];
                                            if(!empty($possibility['description'])) {
                                                echo ' <button type="button" class="btn btn-sm" data-toggle="popover" data-content="' . $possibility['description'] . '"><span class="oi oi-question-mark"></span></button>';
                                            } ?>
                                        </th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php Sandwich::display_reservation_table_row($day, $possibilities, $is_special); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php }
}

?>