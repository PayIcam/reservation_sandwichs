<?php

require '_header.php';

$sandwiches = Sandwich::get_all();
$config = new Config();

$title = "Configuration générale des réservations";

require 'templates/header.php';
require 'templates/edit_config.php';
require 'templates/sandwich_list.php';
require 'templates/edit_sandwich.php';

$title = 'Edition des paramètres généraux des réservations';

?>