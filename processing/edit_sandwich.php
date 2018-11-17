<?php

require '../_header.php';

if(!empty($_POST)) {
    if(isset($_POST['sandwich_id']) && isset($_POST['name']) && isset($_POST['default_quota']) && isset($_POST['description'])) {

        if(!empty($_POST['sandwich_id'])) {
            $sandwich = [
                "sandwich_id" => htmlspecialchars($_POST['sandwich_id']),
                "name" => htmlspecialchars($_POST['name']),
                "default_quota" => htmlspecialchars($_POST['default_quota']),
                "description" => htmlspecialchars($_POST['description'])
            ];

            try {
                Sandwich::update($sandwich);
                Functions::flash("Sandwich mis à jour", "success", $_CONFIG['public_url'] . 'admin_general_settings.php');
            } catch(Exception $e) {
                Functions::flash("Il y a eu une erreur lors de l'insertion", "danger", $_CONFIG['public_url'] . 'edit_sandwich.php?sandwich_id=' . $_POST['sandwich_id']);
            }
        }

        else {
            $sandwich = [
                "name" => htmlspecialchars($_POST['name']),
                "default_quota" => htmlspecialchars($_POST['default_quota']),
                "description" => htmlspecialchars($_POST['description'])
            ];

        	try {
                Sandwich::insert($sandwich);
                header('Location: ../admin_general_settings.php');
                Functions::flash("Sandwich créé", "success", $_CONFIG['public_url'] . 'admin_general_settings.php');
            } catch(Exception $e) {
                Functions::flash("Il y a eu une erreur lors de la mise à jour", "danger", $_CONFIG['public_url'] . 'edit_sandwich.php?sandwich_id=' . $_POST['sandwich_id']);
        	}
        }

    } else {
        Functions::flash("Les bonnes données n'ont pas été transmises", "danger", $_CONFIG['public_url'] . 'edit_sandwich.php');
    }
}
else {
    Functions::flash("Rien n'a été transmis", "danger", $_CONFIG['public_url'] . 'edit_sandwich.php');
}