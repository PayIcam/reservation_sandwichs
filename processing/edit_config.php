<?php

require '../_header.php';

if(!empty($_POST)) {
    if(isset($_POST['days_displayed']) && isset($_POST['default_quota']) && isset($_POST['default_reservation_closure_time']) && isset($_POST['default_pickup_time'])) {
        $config = [
            "days_displayed" => htmlspecialchars($_POST['days_displayed']),
            "default_quota" => htmlspecialchars($_POST['default_quota']),
            "default_reservation_closure_time" => htmlspecialchars($_POST['default_reservation_closure_time']),
            "default_pickup_time" => htmlspecialchars($_POST['default_pickup_time'])
        ];

        Config::update($config);
        header('Location: ../edit_config.php');
    } else {
        echo "Les bonnes données n'ont pas été transmises";
    }
}
else {
    echo "Aucune donnée n'a été reçue";
}