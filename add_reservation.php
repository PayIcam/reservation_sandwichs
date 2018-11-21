<?php

require '_header.php';

if(isset($_GET['day_id'])) {
    $day = new Day($_GET['day_id']);
    if(!$day->can_book()) {
        Functions::flash("En tout cas vous êtes très marrants", "danger", $_CONFIG['public_url'] . 'admin_homepage.php');
    }
    $sandwiches = $day->get_sandwiches_quota();
    $possibilities = Possibility::get_all();

    $title = 'Ajouter une réservation';
} else {
    Functions::flash("Il faut préciser à quel jour vous voulez ajouter des réservations", "danger", $_CONFIG['public_url']);
}

require 'templates/header.php';
require 'templates/add_reservation.php';
