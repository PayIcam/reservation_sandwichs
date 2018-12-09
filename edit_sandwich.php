<?php

require '_header.php';

if(!isset($_GET['sandwich_id'])) {
    header('Location: admin_general_settings.php');
} else {
    $sandwichh = new Sandwich($_GET['sandwich_id']);
    if($sandwichh->is_special == 1) {
        $is_special_input = 'checked disabled';
        $closure_type_input = 'checked disabled';
    } else {
        $is_special_input = 'disabled';
        if($sandwichh->closure_type == 1) {
            $closure_type_input = 'checked';
        } else {
            $closure_type_input = '';
        }
    }
    $title = "Edition d'un sandwich";

    require 'templates/header.php';
    require 'templates/edit_sandwich.php';
}

$title = 'Edition de sandwich';


?>