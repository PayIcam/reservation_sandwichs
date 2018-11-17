<?php

require '_header.php';

$config = new Config();
$days = Day::get_all($config->days_displayed, false, false);
$possibilities = Possibility::get_all();

foreach($days as $day) {
    if(!empty($day['reservation'])) {
        if(Day::closure_is_passed($day) && $day['reservation']['status'] == 'W') {
            var_dump($day['reservation']['status']);
            var_dump(Day::closure_is_passed($day));
            header('Location: processing/cancel_reservation.php?reservation_id=' . $day['reservation']['reservation_id']);
            die();
        }
    }
}

require 'templates/header.php';
require 'templates/homepage.php';

?>