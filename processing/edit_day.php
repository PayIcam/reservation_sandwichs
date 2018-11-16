<?php

require '../_header.php';

if(!empty($_POST)) {
    if(isset($_POST['day_id']) && !empty($_POST['quota']) && !empty($_POST['reservation_opening_date']) && !empty($_POST['reservation_closure_date']) && !empty($_POST['pickup_date']) && !empty($_POST['sandwiches'])) {

        $opening_date = date('Y-m-d H:i:s', date_create_from_format('m/d/Y h:i a', $_POST['reservation_opening_date'])->getTimestamp());
        $closure_date = date('Y-m-d H:i:s', date_create_from_format('m/d/Y h:i a', $_POST['reservation_closure_date'])->getTimestamp());
        $pickup_date = date('Y-m-d H:i:s', date_create_from_format('m/d/Y h:i a', $_POST['pickup_date'])->getTimestamp());

        if(!($opening_date < $closure_date && $closure_date < $pickup_date)) {
            echo 'Les dates ne sont pas logiques';
            die();
        }

        if(empty($_POST['day_id'])) {
            if(Day::already_created($pickup_date)) {
                echo 'Le jour existe déjà...';
                die();
            }
            $day = [
                "quota" => htmlspecialchars($_POST['quota']),
                "reservation_opening_date" => $opening_date,
                "reservation_closure_date" => $closure_date,
                "pickup_date" => $pickup_date
            ];
            $day_id = Day::insert($day, json_decode($_POST['sandwiches']));
        } else {
            $day_id = htmlspecialchars($_POST['day_id']);
            $day = [
                "day_id" => $day_id,
                "quota" => htmlspecialchars($_POST['quota']),
                "reservation_opening_date" => $opening_date,
                "reservation_closure_date" => $closure_date,
                "pickup_date" => $pickup_date
            ];
            Day::update($day, json_decode($_POST['sandwiches']));
        }

        header('Location: ../edit_day.php?day_id=' . $day_id);
    } else {
        echo "Les bonnes données n'ont pas été transmises";
    }
}
else {
    echo "Aucune donnée n'a été reçue";
}

die();