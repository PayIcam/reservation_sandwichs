<?php

require '_header.php';

$sandwiches = Sandwich::get_all();

require 'templates/header.php';
require 'templates/sandwich_list.php';
require 'templates/edit_sandwich.php';

?>