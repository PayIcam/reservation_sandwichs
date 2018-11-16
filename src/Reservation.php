<?php

class Reservation {
    public $reservation_id;
    public $firstname;
    public $lastname;
    public $email;
    public $promo;
    public $status;
    public $payicam_transaction_id;
    public $payicam_transaction_url;
    public $reservation_date;
    public $payment_date;
    public $pickup_date;
    public $possibility_id;
    public $day_id;
    public $sandwich_id;

    public function __construct($reservation_id) {
        global $db;
        $this->bind($db->queryFirst('SELECT * FROM reservations WHERE reservation_id = :reservation_id', array('reservation_id' => $reservation_id)));
    }

    public static function get_days_stats() {
        global $db;
        $days_stats = $db->query('SELECT SUM(CASE WHEN status="V" THEN 1 ELSE 0 END) reservations, SUM(CASE WHEN status="W" THEN 1 ELSE 0 END) pendings, SUM(CASE WHEN r.pickup_date IS NOT NULL THEN 1 ELSE 0 END) picked_ups, date(d.pickup_date) day, d.* FROM reservations r LEFT JOIN days d ON d.day_id=r.day_id WHERE status IN("V", "W") AND (CURDATE() <= DATE(d.pickup_date) OR (WEEK(CURDATE()) = WEEK(d.pickup_date) AND YEAR(CURDATE()) = YEAR(d.pickup_date))) GROUP BY d.day_id, date(d.pickup_date) UNION SELECT 0 reservations, 0 pendings, 0 picked_ups, date(d.pickup_date) day, d.* FROM days d LEFT JOIN reservations r ON r.day_id=d.day_id WHERE status NOT IN ("V", "W") OR status IS NULL ORDER BY day');
        foreach($days_stats as &$day_stats) {
            $sandwiches_stats = $db->query('SELECT SUM(CASE WHEN status="V" THEN 1 ELSE 0 END) reservations, SUM(CASE WHEN status="W" THEN 1 ELSE 0 END) pendings, SUM(CASE WHEN pickup_date IS NOT NULL THEN 1 ELSE 0 END) picked_ups, s.sandwich_id, CASE WHEN dhs.quota IS NOT NULL THEN dhs.quota ELSE s.default_quota END quota
                FROM reservations r
                LEFT JOIN day_has_sandwiches dhs ON dhs.day_id=r.day_id AND dhs.sandwich_id=r.sandwich_id
                RIGHT JOIN sandwiches s ON dhs.sandwich_id=s.sandwich_id
                WHERE r.day_id=:day_id and status IN ("V","W")
                GROUP BY s.sandwich_id, CASE WHEN dhs.quota IS NOT NULL THEN dhs.quota ELSE s.default_quota END',
                array('day_id' => $day_stats['day_id']));
            $day_stats['sandwiches_stats'] = $sandwiches_stats;
        }
        return $days_stats;
    }

    public static function get_choices_stats($day_id) {
        global $db;
        return $db->query('SELECT s.name sandwich, p.name possibility, COUNT(*) reservations , SUM(CASE WHEN r.pickup_date IS NOT NULL THEN 1 ELSE 0 END) picked_ups FROM reservations r LEFT JOIN purchases_possibilities p ON p.possibility_id=r.possibility_id LEFT JOIN sandwiches s ON s.sandwich_id=r.sandwich_id WHERE status="V" and r.day_id=:day_id GROUP BY s.name, p.name', array('day_id' => $day_id));
    }

    public static function get_all($day_id=false, $status=false) {
        global $db;
        if($day_id !== false && $status === false) {
            return $db->query('SELECT r.*, p.name possibility, s.name sandwich FROM reservations r LEFT JOIN sandwiches s ON s.sandwich_id=r.sandwich_id LEFT JOIN purchases_possibilities p ON p.possibility_id=r.possibility_id WHERE day_id=:day_id', array('day_id' => $day_id));
        } elseif($day_id !== false && $status !== false) {
            return $db->query('SELECT r.*, p.name possibility, s.name sandwich FROM reservations r LEFT JOIN sandwiches s ON s.sandwich_id=r.sandwich_id LEFT JOIN purchases_possibilities p ON p.possibility_id=r.possibility_id WHERE day_id=:day_id and status=:status', array('day_id' => $day_id, 'status' => $status));
        }elseif($status !== false) {
            return $db->query('SELECT r.*, p.name possibility, s.name sandwich FROM reservations r LEFT JOIN sandwiches s ON s.sandwich_id=r.sandwich_id LEFT JOIN purchases_possibilities p ON p.possibility_id=r.possibility_id WHERE status=:status', array('status' => $status));
        }
    }

    public static function get_own($mail, $status=false) {
        if($status !== false) {
            return $db->query('SELECT * FROM reservations WHERE email=:email and status=:status', array('email' => $email, 'status' => $status));
        } else {
            return $db->query('SELECT * FROM reservations WHERE email=:email', array('email' => $email));
        }
    }

    public static function insert($reservation) {
        global $db;
        return $db->query('INSERT INTO reservations(firstname, lastname, email, promo, status, payicam_transaction_id, payicam_transaction_url, possibility_id, day_id, sandwich_id) VALUES (:firstname, :lastname, :email, :promo, "W", :payicam_transaction_id, :payicam_transaction_url, :possibility_id, :day_id, :sandwich_id)', array("firstname" => $reservation['firstname'], "lastname" => $reservation['lastname'], "email" => $reservation['email'], "promo" => $reservation['promo'], "payicam_transaction_id" => $reservation['payicam_transaction_id'], "payicam_transaction_url" => $reservation['payicam_transaction_url'], "possibility_id" => $reservation['possibility_id'], "day_id" => $reservation['day_id'], "sandwich_id" => $reservation['sandwich_id']));
    }
    public static function update_reservation($reservation_id, $status) {
        global $db;
        $db->query('UPDATE reservations SET status=:status, payment_date=CURRENT_TIMESTAMP() WHERE reservation_id=:reservation_id', array("reservation_id" => $reservation_id, "status" => $status));
    }
    public function pickup_sandwich() {
        global $db;
        $db->query('UPDATE reservations SET pickup_date=CURRENT_TIMESTAMP() WHERE reservation_id=:reservation_id', array("reservation_id" => $this->reservation_id));
        return json_encode(array('message' => 'Tout a bien fonctionné'));
    }
    public function unpickup_sandwich() {
        global $db;
        $db->query('UPDATE reservations SET pickup_date=NULL WHERE reservation_id=:reservation_id', array("reservation_id" => $this->reservation_id));
        return json_encode(array('message' => 'Tout a bien fonctionné'));
    }

    public function toggle() {
        if(empty($this->pickup_date)) {
            return $this->pickup_sandwich();
        } else {
            return $this->unpickup_sandwich();
        }
    }


    public static function make_transaction($possibility_id) {
        global $payutcClient, $_CONFIG;
        $article_id = Possibility::get_article_id($possibility_id)['article_id'];

        return $payutcClient->createTransaction(array(
            "items" => json_encode(array(array($article_id, 1))),
            "fun_id" => $_CONFIG['cafet_fun_id'],
            "mail" => $_SESSION['icam_informations']->mail,
            "return_url" => $_CONFIG['public_url']."callback", // En fait ça passe pq shotgun regarde lui mm si le status de la transaction est tjs à W si elle n'a pas été mise à jour
            "callback_url" => $_CONFIG['public_url']."callback" // N'est même pas utilisé pour le moment ...
        ));
    }

    public static function check_status($reservation) {
        global $_CONFIG, $payutcClient;
        $payicam_transaction = $payutcClient->getTransactionInfo(array('fun_id' => $_CONFIG['cafet_fun_id'], 'tra_id' => $reservation['payicam_transaction_id']));
        if($reservation['status'] != $payicam_transaction->status) {
            self::update_reservation($reservation['reservation_id'], $payicam_transaction->status);
        } elseif($reservation['status'] == 'W' && time() - strtotime($reservation['reservation_date']) > 15*60) {
            self::update_reservation($reservation['reservation_id'], 'A');
        }
    }

    public static function display_reservation_name($reservation_id) {
        global $db;
        $names = $db->queryFirst('SELECT s.name sandwich, p.name choice FROM reservations r LEFT JOIN sandwiches s ON s.sandwich_id=r.sandwich_id LEFT JOIN purchases_possibilities p ON p.possibility_id = r.possibility_id WHERE reservation_id=:reservation_id', array('reservation_id' => $reservation_id));

        return $names['choice'] . ' ' . $names['sandwich'];
    }

    public static function display_pickup_button($reservation) {
        if(empty($reservation['pickup_date'])) { ?>
            <button data-reservation_id="<?=$reservation['reservation_id']?>" class="btn btn-success pickup"><span class="oi oi-check"></span></button>
        <?php } else { ?>
            <button data-reservation_id="<?=$reservation['reservation_id']?>" class="btn btn-danger unpickup"><span class="oi oi-x"></span></button>
        <?php }
    }

    protected function bind($reservation) {
        $this->reservation_id = $reservation['reservation_id'];
        $this->firstname = $reservation['firstname'];
        $this->lastname = $reservation['lastname'];
        $this->email = $reservation['email'];
        $this->promo = $reservation['promo'];
        $this->status = $reservation['status'];
        $this->payicam_transaction_id = $reservation['payicam_transaction_id'];
        $this->payicam_transaction_url = $reservation['payicam_transaction_url'];
        $this->reservation_date = $reservation['reservation_date'];
        $this->payment_date = $reservation['payment_date'];
        $this->pickup_date = $reservation['pickup_date'];
        $this->possibility_id = $reservation['possibility_id'];
        $this->day_id = $reservation['day_id'];
        $this->sandwich_id = $reservation['sandwich_id'];
    }
}

?>