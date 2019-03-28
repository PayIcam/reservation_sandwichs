<?php

require '../_header.php';

if(!empty($_GET)) {
    if(isset($_GET['day_id'])) {
        if(!Day::cant_remove_day($_GET['day_id'])) {
            Day::remove_day($_GET['day_id']);
            Functions::flash("Jour supprimé !", "success", $_CONFIG['public_url'] . 'admin_homepage.php');
        } else {
            Functions::flash("Ce jour ne peut pas être supprimé. Il y a déjà eu des réservations.", "danger", $_CONFIG['public_url'] . 'admin_homepage.php');
        }
    } else {
        Functions::flash("Les bonnes données n'ont pas été transmises", "danger", $_CONFIG['public_url'] . 'admin_homepage.php');
    }
}
else {
    Functions::flash("Rien n'a été transmis", "danger", $_CONFIG['public_url'] . 'admin_homepage.php');
}

