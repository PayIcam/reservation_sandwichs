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
            } catch(Exception $e) {
                echo "Il y a eu une erreur lors de l'update";
                die();
            }
            header('Location: ../admin_sandwichs.php');
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
            } catch(Exception $e) {
                echo "Il y a eu une erreur lors de l'insertion";
        	}
        }

    } else {
        echo "Les bonnes données n'ont pas été transmises";
    }
}
else {
	echo "Aucune donnée n'a été reçue";
}