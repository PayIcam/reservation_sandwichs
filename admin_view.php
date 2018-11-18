<?php

require '_header.php';

if(isset($_GET['day_id'])) {
    $day = new Day($_GET['day_id']);
    $sql_choices_stats = Reservation::get_choices_stats($_GET['day_id']);
    $possibilities = array_unique(array_column($sql_choices_stats, 'possibility'));
    $sandwiches = array_unique(array_column($sql_choices_stats, 'sandwich'));
    $choices_stats = [];
    foreach($sandwiches as $sandwich) {
        $sandwich_choices_stats = [];
        foreach($sql_choices_stats as $choice_stats) {
            if($choice_stats['sandwich'] == $sandwich) {
                array_push($sandwich_choices_stats, $choice_stats);
            }
        }
        array_push($choices_stats, $sandwich_choices_stats);
    }
    $reservations = Reservation::get_all($_GET['day_id'], 'V');
} else {
    header('Location: admin_homepage.php');
}

$title = "Vue d'un jour";

require 'templates/header.php';
require 'templates/admin_view.php';
