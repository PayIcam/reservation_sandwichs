<?php

require 'class/DB.php';
require 'class/Sandwich.php';
require 'class/Config.php';
require 'class/Day.php';

$_CONFIG = require 'config.php';

$db = new DB($_CONFIG['database']['sql_host'], $_CONFIG['database']['sql_db'], $_CONFIG['database']['sql_login'], $_CONFIG['database']['sql_pass']);
