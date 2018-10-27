<?php

require '../_header.php';
if(!empty($_POST)) {
	$insertion = $db->prepare('INSERT INTO sandwichs(name, default_quota) VALUES (:name, :default_quota)');
	if(!$insertion->execute(["name" => $_POST['name'], "default_quota" => $_POST['quota']])) {
		echo 'AHHHHHHHHHHHHHHHHHHHH';
	} else {
		echo "l'anneau est détruit";
	}
}
else {
	header("Location: https://www.youtube.com/watch?v=LML6SoNE7xE");
}