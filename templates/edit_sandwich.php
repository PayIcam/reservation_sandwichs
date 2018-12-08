<div class="container">
	<h1 class="text-center"><?= isset($sandwichh) ? "Editer le sandwich" : "Ajouter un sandwich" ?></h1>
	<form method='POST' action="processing/edit_sandwich.php">
        <input type="hidden" name="sandwich_id" value="<?=$_GET['sandwich_id'] ?? ""?>">
		<div class="form-group">
			<label for="name">Nom du sandwich :</label>
			<input type="text" class="form-control" name="name" id="name" required value="<?= $sandwichh->name ?? '' ?>">
		</div>
		<div class="form-group">
			<label for="default_quota">Quota de base du sandwich :</label>
			<input type="number" class="form-control" name="default_quota" id="default_quota" required value="<?= $sandwichh->default_quota ?? '' ?>">
		</div>
        <div class="form-group">
            <label for="description">Description du sandwich :</label>
            <textarea class="form-control" name="description" id="description" rows="1"><?= $sandwichh->description ?? '' ?></textarea>
        </div>
		<div class="form-group">
			<label for="description">Description du sandwich :</label>
			<textarea class="form-control" name="description" id="description" rows="1"><?= $sandwichh->description ?? '' ?></textarea>
		</div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_special" value="1" id="is_special" <?= isset($sandwichh->is_special) ? $sandwichh->is_special==1 ? 'checked disabled' : 'disabled' : ''  ?>>
            <label class="form-check-label" for="is_special">Sandwich spécial</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="closure_type" value="1" id="closure_type" <?= isset($sandwichh->is_special) ? $sandwichh->is_special==1 ? 'disabled' : '' : ''  ?><?= isset($sandwichh->closure_type) ? $sandwichh->closure_type==1 ? 'checked' : '' : ''  ?>>
            <label class="form-check-label" for="closure_type">Finit plus tôt</label>
        </div>
        <br>
	<button type="submit" class="btn btn-primary">Submit</button>
	</form>
    <script src="js/edit_sandwich.js"></script>
</div>