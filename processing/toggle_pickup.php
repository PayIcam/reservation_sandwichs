<?php

require '../_header.php';

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if(!empty($_GET)) {
            if(isset($_GET['reservation_id'])) {
                $reservation = new Reservation(htmlspecialchars($_GET['reservation_id']));
                echo $reservation->toggle();
            } else {
                echo json_encode(array('message' => "Les bonnes données n'ont pas été transmises"));
            }
        }
        else {
            echo json_encode(array('message' => "Aucune donnée n'a été reçue"));
        }
    } else {
    Functions::flash('A quoi jouez vous ?', 'warning', $_CONFIG['public_url']);
    }
} else {
    Functions::flash('A quoi jouez vous ?', 'warning', $_CONFIG['public_url']);
}
