<?php

require '../_header.php';

$day_sandwiches = Sandwich::get_all(false);
$config = new Config();

for ($i=0; $i <= 4; $i++) { 
    $day = date('Y-m-d', strtotime('+' . $i . ' day', strtotime('next monday')));

    $opening_date = date('Y-m-d H:i:s');
    $first_closure_date = $day . ' ' . $config->default_reservation_first_closure_time;
    $second_closure_date = $day . ' ' . $config->default_reservation_second_closure_time;
    $pickup_date = $day . ' ' . $config->default_pickup_time;

    if(!($opening_date < $first_closure_date && $first_closure_date < $second_closure_date && $second_closure_date < $pickup_date)) {
        Functions::flash("Les dates du jour ne sont pas logiques", "warning", $_CONFIG['public_url'] . 'admin_homepage.php');
    }

    $already_created = Day::already_created($pickup_date);

    if($already_created == 1) {
        Functions::flash("Le jour " . $i . " existe déjà", "warning");
        continue;
    } elseif($already_created == 2) {
        Day::restore($pickup_date);
        Functions::flash("Le jour " . $i . " existait déjà et a été restauré", "info");
        continue;
    }
    $day = [
        "quota" => htmlspecialchars($config->default_quota),
        "reservation_opening_date" => $opening_date,
        "reservation_first_closure_date" => $first_closure_date,
        "reservation_second_closure_date" => $second_closure_date,
        "pickup_date" => $pickup_date
    ];

    $day_id = Day::insert($day, json_decode(json_encode($day_sandwiches)));
    Functions::flash("Jour " . $i . " ajouté", "success");
}

header('Location: ' . $_CONFIG['public_url'] . 'admin_homepage.php');
die();