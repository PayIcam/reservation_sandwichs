<?php

require 'config.php';

function connect_to_db($conf) {
    try {
        $db = new PDO('mysql:host='.$conf['sql_host'].';dbname='.$conf['sql_db'].';charset=utf8',$conf['sql_user'],$conf['sql_pass'],array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
        return $db;
    } catch(Exeption $e) {
        die('erreur:'.$e->getMessage());
    }
}

$db = connect_to_db($_CONFIG['database_connection']);