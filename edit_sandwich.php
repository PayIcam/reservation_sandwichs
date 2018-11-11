<?php

require '_header.php';

if(!isset($_GET['sandwich_id'])) {
    header('Location: admin_sandwichs.php');
} else {
    $sandwich = new Sandwich($_GET['sandwich_id']);

    require 'templates/header.php';
    require 'templates/edit_sandwich.php';
}


?>