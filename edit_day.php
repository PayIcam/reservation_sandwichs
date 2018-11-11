<?php

require '_header.php';

if(isset($_GET['day_id'])) {
    $day = new Day($_GET['day_id']);
    $day_sandwiches = $day->get_day_sandwiches();
} else {
    $day_sandwiches = Sandwich::get_all(false);
}

require 'templates/header.php';
require 'templates/edit_day.php';
