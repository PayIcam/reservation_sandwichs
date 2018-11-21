<?php

require '_header.php';

function sorting($a, $b){
    return $a['possibility'] <= $b['possibility'] ? -1:1;
}
function sorting_possibilities($a, $b){
    return $a <= $b ? -1:1;
}

if(isset($_GET['day_id'])) {
    $day = new Day($_GET['day_id']);
    $sql_choices_stats = Reservation::get_choices_stats($_GET['day_id']);
    $possibilities = array_unique(array_column($sql_choices_stats, 'possibility'));
    $sandwiches = array_unique(array_column($sql_choices_stats, 'sandwich'));
    $possibilities = array_unique(array_column($sql_choices_stats, 'possibility'));
    usort($possibilities, 'sorting_possibilities');
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
    foreach($choices_stats as &$choice_stats) {
        $current_possibilities = array_column($choice_stats, 'possibility');
        foreach(array_diff($possibilities, $current_possibilities) as $possibility) {
            $array = [
                'sandwich' => $choice_stats[0]['sandwich'],
                'possibility' => $possibility,
                'reservations' => 0,
                'picked_ups' => 0
            ];
            array_push($choice_stats, $array);
        }
        usort($choice_stats, 'sorting');
    }
    //Pas optimisé du tout du tout du tout du tout, mais ça devient relou

    $reservations = Reservation::get_all($_GET['day_id'], 'V');
} else {
    header('Location: admin_homepage.php');
}

$title = "Vue d'un jour";

require 'templates/header.php';
require 'templates/admin_view.php';
