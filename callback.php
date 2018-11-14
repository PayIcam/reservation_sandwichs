<?php

require '_header.php';

if(isset($_SESSION['icam_informations']->email)) {
    $self_pendings = Reservation::get_own($_SESSION['icam_informations']['email'], 'W');
    foreach($self_pendings as $reservation) {
        Reservation::check_status($reservation);
    }
} else {
    $pendings = Reservation::get_all(false, 'W');
    foreach($pendings as $reservation) {
        Reservation::check_status($reservation);
    }
}

header('Location: ' . $_CONFIG['public_url']);
die();