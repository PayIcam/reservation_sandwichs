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
        Functions::flash("Configuration mise à jour", "success", $_CONFIG['public_url'] . 'admin_general_settings.php');
    } else {
        Functions::flash("Les bonnes données n'ont pas été transmises", "danger", $_CONFIG['public_url'] . 'admin_general_settings.php');
    }
}
else {
    Functions::flash("Rien n'a été transmis", "danger", $_CONFIG['public_url'] . 'admin_general_settings.php');
}
