<?php

require '_header.php';

function sorting($a, $b){
    return $a['possibility'] <= $b['possibility'] ? -1:1;
}
function sorting_possibilities($a, $b){
    return $a <= $b ? -1:1;
}

if(isset($_GET['day_id'])) {
    $day = new Day($_GET['day_id']);
    $sandwiches_stats = Day::get_sandwich_day_stats(array('day_id' => $_GET['day_id'], 'demi_ids' => $_CONFIG['demi_purchase_ids']));
    $reservations = Reservation::get_all($_GET['day_id'], 'V');
} else {
    Functions::Flash("Le jour n'est pas spécifié", "danger", "admin_homepage.php");
}

$title = "Vue d'un jour";

require 'templates/header.php';
require 'templates/admin_view.php';
