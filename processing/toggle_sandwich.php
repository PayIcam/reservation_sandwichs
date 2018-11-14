<?php

require '../_header.php';

if(!empty($_GET)) {
    if(isset($_GET['sandwich_id'])) {
        $sandwich = new Sandwich(htmlspecialchars($_GET['sandwich_id']));
        echo $sandwich->toggle();
    } else {
        echo json_encode(array('message' => "Les bonnes données n'ont pas été transmises"));
    }
}
else {
    echo json_encode(array('message' => "Aucune donnée n'a été reçue"));
}