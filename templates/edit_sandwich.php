<div class="container">
	<h1 class="text-center"><?= isset($sandwich) ? "Editer le sandwich" : "Ajouter un sandwich" ?></h1>
	<form method='POST' action="processing/edit_sandwich.php">
        <input type="hidden" name="sandwich_id" value="<?=$_GET['sandwich_id'] ?? ""?>">
		<div class="form-group">
			<label for="name">Nom du sandwich :</label>
			<input type="text" class="form-control" name="name" id="name" required value="<?= $sandwich->name ?? '' ?>">
		</div>
		<div class="form-group">
			<label for="default_quota">Quota de base du sandwich :</label>
			<input type="number" class="form-control" name="default_quota" id="default_quota" required value="<?= $sandwich->default_quota ?? '' ?>">
		</div>
		<div class="form-group">
			<label for="description">Description du sandwich :</label>
			<textarea class="form-control" name="description" id="description" rows="1"><?= $sandwich->description ?? '' ?></textarea>
		</div>
		<button type="submit" class="btn btn-primary">Submit</button>
	</form>
</div>