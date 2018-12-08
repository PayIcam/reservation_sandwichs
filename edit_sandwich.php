<?php

require '_header.php';

if(!isset($_GET['sandwich_id'])) {
    header('Location: admin_general_settings.php');
} else {
    $sandwichh = new Sandwich($_GET['sandwich_id']);
    $title = "Edition d'un sandwich";

    require 'templates/header.php';
    require 'templates/edit_sandwich.php';
}

$title = 'Edition de sandwich';


?>