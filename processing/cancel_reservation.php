<?php

require '../_header.php';

if(isset($_GET['reservation_id'])) {

    $reservation = new Reservation($_GET['reservation_id']);
    if($reservation->status=='W') {
        Reservation::update_reservation($reservation->reservation_id, "A");
    }
}

header('Location: ' . $_CONFIG['public_url']);
die();
