<?php

require '../_header.php';

if(!empty($_GET)) {
    if(isset($_GET['reservation_id'])) {
        $reservation = new Reservation(htmlspecialchars($_GET['reservation_id']));
        echo $reservation->toggle();
    } else {
        echo json_encode(array('message' => "Les bonnes données n'ont pas été transmises"));
    }
}
else {
    echo json_encode(array('message' => "Aucune donnée n'a été reçue"));
}