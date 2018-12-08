<?php

require '../_header.php';

if(!empty($_POST)) {
    if(isset($_POST['day_id']) && !empty($_POST['quota']) && !empty($_POST['reservation_opening_date']) && !empty($_POST['reservation_first_closure_date']) && !empty($_POST['reservation_second_closure_date']) && !empty($_POST['pickup_date']) && !empty($_POST['sandwiches'])) {

        $opening_date = date('Y-m-d H:i:s', date_create_from_format('m/d/Y h:i a', $_POST['reservation_opening_date'])->getTimestamp());
        $first_closure_date = date('Y-m-d H:i:s', date_create_from_format('m/d/Y h:i a', $_POST['reservation_first_closure_date'])->getTimestamp());
        $second_closure_date = date('Y-m-d H:i:s', date_create_from_format('m/d/Y h:i a', $_POST['reservation_second_closure_date'])->getTimestamp());
        $pickup_date = date('Y-m-d H:i:s', date_create_from_format('m/d/Y h:i a', $_POST['pickup_date'])->getTimestamp());

        if(!($opening_date < $first_closure_date && $first_closure_date < $second_closure_date && $second_closure_date < $pickup_date)) {
            Functions::flash("Les dates ne sont pas logiques", "warning", $_CONFIG['public_url'] . 'edit_day.php?day_id=' . $_POST['day_id']);
        }

        if(empty($_POST['day_id'])) {
            if(Day::already_created($pickup_date)) {
                Functions::flash("Le jour existe déjà", "warning", $_CONFIG['public_url'] . 'edit_day.php');
            }
            $day = [
                "quota" => htmlspecialchars($_POST['quota']),
                "reservation_opening_date" => $opening_date,
                "reservation_first_closure_date" => $first_closure_date,
                "reservation_second_closure_date" => $second_closure_date,
                "pickup_date" => $pickup_date
            ];
            $day_id = Day::insert($day, json_decode($_POST['sandwiches']));
            Functions::flash("Jour ajouté", "success", $_CONFIG['public_url'] . 'edit_day.php');
        } else {
            $day_id = htmlspecialchars($_POST['day_id']);
            if(Day::already_created($pickup_date, $_POST['day_id'])) {
                Functions::flash("Le jour existe déjà", "warning", $_CONFIG['public_url'] . 'edit_day.php?day_id=' . $_POST['day_id']);
            } elseif(Day::cant_change_day($pickup_date, $_POST['day_id'])) {
                Functions::flash("Impossible de changer de jour alors que des utilisateurs ont déjà réservé", "warning", $_CONFIG['public_url'] . 'edit_day.php?day_id=' . $_POST['day_id']);
            }
            $day = [
                "day_id" => $day_id,
                "quota" => htmlspecialchars($_POST['quota']),
                "reservation_opening_date" => $opening_date,
                "reservation_first_closure_date" => $first_closure_date,
                "reservation_second_closure_date" => $second_closure_date,
                "pickup_date" => $pickup_date
            ];
            Day::update($day, json_decode($_POST['sandwiches']));
            Functions::flash("Jour mis à jour", "success", $_CONFIG['public_url'] . 'admin_general_settings.php');
        }
    } else {
        Functions::flash("Les bonnes données n'ont pas été transmises", "danger", $_CONFIG['public_url'] . 'edit_day.php');
    }
}
else {
    Functions::flash("Rien n'a été transmis", "danger", $_CONFIG['public_url'] . 'edit_day.php');
}

