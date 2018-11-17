<?php

require '../_header.php';

if(isset($_GET['reservation_id'])) {
    $reservation = new Reservation($_GET['reservation_id']);

    if($reservation->email != $_SESSION['icam_informations']->mail) {
        Functions::flash("Vous essayez d'annuler des réservations qui ne sont pas les votres ???", 'danger', $_CONFIG['public_url']);
    }

    if($reservation->status=='W') {
        if($reservation->status != htmlspecialchars($_GET['status'])) {
            Functions::flash("Votre transaction n'est plus en attente", 'danger', $_CONFIG['public_url']);
        }
        Reservation::update_reservation($reservation->reservation_id, "A");
        Functions::flash("Votre réservation a bien été annulée", 'success', $_CONFIG['public_url']);
    } elseif($reservation->status=='V') {
        if($reservation->status != htmlspecialchars($_GET['status'])) {
            Functions::flash("Votre réservation a déjà été annulée", 'danger', $_CONFIG['public_url']);
        }
        $reservation->refound_cancel_reservation();
        Functions::flash("Votre réservation a bien été annulée, vous avez été remboursé.", 'success', $_CONFIG['public_url']);
    } else {
        Functions::flash("Votre réservation a déjà été annulée", 'warning', $_CONFIG['public_url']);
    }
}
