<?php

require '_header.php';

$config = new Config();
$days = Day::get_all($config->days_displayed, false, false);
$possibilities = Possibility::get_all();

require 'templates/header.php';
require 'templates/homepage.php';

?>