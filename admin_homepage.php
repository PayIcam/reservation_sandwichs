<?php

require '_header.php';

$days_stats = Reservation::get_days_stats();
$sandwiches = Sandwich::get_all(true, true);
$sandwiches_ids = array_column($sandwiches, 'sandwich_id');

function sorting($a, $b){
    return $a['sandwich_id'] <= $b['sandwich_id'] ? -1:1;
}


foreach($days_stats as &$day_stats) {
    if(!empty($day_stats['sandwiches_stats'])) {
        $sandwiches_ids_stats = array_column($day_stats['sandwiches_stats'], 'sandwich_id');
        $difference_ids = array_diff($sandwiches_ids, $sandwiches_ids_stats);
    } else {
        $difference_ids = $sandwiches_ids;
    }
    foreach($difference_ids as $id) {
        $array = ['reservations' => 0, 'pendings' => 0, 'picked_ups' => 0, 'sandwich_id' => $id, 'quota' => 0];
        array_push($day_stats['sandwiches_stats'], $array);
    }

    usort($day_stats['sandwiches_stats'], 'sorting');
}

$title = "Accueil de l'administration";

require 'templates/header.php';
require 'templates/admin_homepage.php';

?>