<div class="container">
    <h1 class="text-center">Ajouter un participant</h1>
    <br><br><br>
    <div class="form-group">
        <label for="typeahead"><h2>Cherchez ici la personne si elle a PayIcam.</h2></label>
        <input type="text" class="typeahead-user form-control" name="typeahead" id="typeahead">
    </div>
    <hr>
    <form method='POST' action="processing/add_reservation.php?day_id=<?=$_GET['day_id']?>">
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" name="firstname" id="firstname" required>
            </div>
            <div class="col-sm-4 form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" name="lastname" id="lastname" required>
            </div>
            <div class="col-sm-4 form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" id="email" required>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3 form-group">
                <label for="sel1">Promo:</label>
                <input type="hidden" name="promo" class="form-control">
                <select class="form-control" name="promo">
                    <option disabled>Entrez la promo du participant</option>
                    <?php foreach(['123', '122', '121', '120', '119', '2023', '2022', '2021', '2020', '2019', '24'] as $promo) {
                        echo '<option>' . $promo . '</option>';
                    } ?>
                    <option value="0">Permanent</option>
                    <option>Autre</option>
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="sel1">Choix du sandwich:</label>
                <select class="form-control" name="sandwich_id">
                    <option disabled>Quel choix de sandwich ?</option>
                    <?php foreach($sandwiches as $sandwich) {
                        if($day->can_book_sandwich($sandwich)) {
                            echo '<option value="' . $sandwich['sandwich_id'] . '">' . $sandwich['name'] . '</option>';
                        }
                    } ?>
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="sel1">Type de sandwich:</label>
                <select class="form-control" name="possibility_id">
                    <option disabled>Quel option d'achat ?</option>
                    <?php foreach($possibilities['classics'] as $possibility) {
                        if($day->can_book_possibility($possibility['closure_type'])) {
                            echo '<option value="' . $possibility['possibility_id'] . '">' . $possibility['name'] . '</option>';
                        }
                    } ?>
                    <?php if($day->can_book()) {
                        foreach($possibilities['specials'] as $possibility) {
                            echo '<option value="' . $possibility['possibility_id'] . '">' . $possibility['name'] . '</option>';
                        }
                    } ?>
                </select>
            </div>
            <div class="col-sm-3 form-group">
                <label for="sel1">Payement:</label>
                <select class="form-control" name="payement">
                    <option disabled> Choisissez le moyen de payement de votre participant</option>
                    <option>Mozart</option>
                    <option>Espèces</option>
                    <option>Gratuit</option>
                    <option>Autre</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<script src="js/libraries/typeahead.min.js"> </script>
<script>
    var promos = '<?=json_encode(['123', '122', '121', '120', '119', '2023', '2022', '2021', '2020', '2019', '24', 'Autre'])?>'
</script>

<script src="js/add_reservation.js"></script>
