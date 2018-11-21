<?php

require '../_header.php';

if(!empty($_GET) && !empty($_POST)) {
    if(isset($_GET['day_id']) && isset($_POST['sandwich_id']) && isset($_POST['possibility_id']) && isset($_POST['lastname']) && isset($_POST['firstname']) && isset($_POST['promo']) && isset($_POST['payement']) && isset($_POST['promo'])) {

        $day_id = (int) $_GET['day_id'];
        $sandwich_id = (int) $_POST['sandwich_id'];
        $possibility_id = (int) $_POST['possibility_id'];

        if(!Reservation::reservation_is_possible($day_id, $sandwich_id)) {
            Functions::flash("Il n'est plus possible de faire ce choix... Rechargez la page.", "warning", $_CONFIG['public_url'].'add_reservation.php?day_id='.$day_id);
        }
        if(Reservation::user_has_reservation_already(array('day_id' => $day_id, 'sandwich_id' => $sandwich_id, 'email' => $_SESSION['icam_informations']->mail))) {
            Functions::flash("Il a déjà une réservation en attente ou validée. Un moment ça suffit l'alcool", "danger", $_CONFIG['public_url'].'add_reservation.php?day_id='.$day_id);
        }

        $firstname = htmlspecialchars($_POST['firstname']);
        $lastname = htmlspecialchars($_POST['lastname']);
        $email = htmlspecialchars($_POST['email']);
        $promo = htmlspecialchars($_POST['promo']);
        $payement = htmlspecialchars($_POST['payement']);

        $insert_array = [
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "promo" => $promo,
            "payement" => $payement,
            "possibility_id" => $possibility_id,
            "day_id" => $day_id,
            "sandwich_id" => $sandwich_id
        ];

        Reservation::insert($insert_array, true);

        Functions::flash('Réservation ajoutée !', 'success', $_CONFIG['public_url'] . 'add_reservation.php?day_id=' . $day_id);
    } else {
        Functions::flash("Les bonnes données n'ont pas été transmises", 'danger', $_CONFIG['public_url'] . 'admin_homepage.php');
    }
}
else {
    Functions::flash("Aucune donnée n'a été reçue", 'danger', $_CONFIG['public_url'] . 'admin_homepage.php');
}
