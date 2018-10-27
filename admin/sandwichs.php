<?php 

require '../_header.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Sandwichs</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
</head>
<body>
	<div class="container-fluid ">
		<h1 class="text-center">Insere ton sandwichs</h1>
		<form method='POST' action="ajout_sandwich.php">
			<div class="form-group">
				<label for="name">Nom du sandwich :</label>
				<input type="text" class="form-control" name="name" id="name" placeholder="Je suis un sandwich" required>
			</div>
			<div class="form-group">
				<label for="quota">Quota :</label>
				<input type="int" class="form-control" name="quota" id="quota" placeholder="Je suis un sandwich" required>
			</div>
			<div class="form-group">
				<label for="description">Description du sandwich :</label>
				<textarea class="form-control" name="description" id="description" rows="1" required></textarea>
			</div>
			<button type="submit" class="btn btn-primary">Submit</button>
		</form>
	</div>
</body>
</html>