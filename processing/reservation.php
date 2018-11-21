<?php

require '../_header.php';

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if(!empty($_GET)) {
            if(isset($_GET['day_id']) && isset($_GET['sandwich_id']) && isset($_GET['possibility_id'])) {
                $day_id = (int) $_GET['day_id'];
                $sandwich_id = (int) $_GET['sandwich_id'];
                $possibility_id = (int) $_GET['possibility_id'];

                if(!Reservation::reservation_is_possible($day_id, $sandwich_id)) {
                    Functions::flash("Il n'est plus possible de prendre votre place... Rechargez la page.", "warning", $_CONFIG['public_url']);
                }

                $payicam_reservation = Reservation::make_transaction($possibility_id);

                $insert_array = [
                    "firstname" => $_SESSION['icam_informations']->prenom,
                    "lastname" => $_SESSION['icam_informations']->nom,
                    "email" => $_SESSION['icam_informations']->mail,
                    "promo" => $_SESSION['icam_informations']->promo,
                    "payicam_transaction_id" => $payicam_reservation->tra_id,
                    "payicam_transaction_url" => $payicam_reservation->url,
                    "possibility_id" => $possibility_id,
                    "day_id" => $day_id,
                    "sandwich_id" => $sandwich_id
                ];

                $reservation_id = Reservation::insert($insert_array);

                echo json_encode(array("message" => "Sandwich commandé !", "url" => $payicam_reservation->url));
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
