<?php

require '_header.php';
if(isset($_GET['day_id'])) {
    $day = new Day($_GET['day_id']);
    $day->export_reservations_csv($_GET['day_id']);
} else {
    Functions::Flash("Le jour n'est pas spécifié", "danger", "admin_homepage.php");
}
