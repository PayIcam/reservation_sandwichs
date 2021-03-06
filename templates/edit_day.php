<div class="container">
    <h1 class="text-center"><?= isset($day) ? "Editer le jour" : "Ajouter un jour" ?></h1>
    <form method='POST' action="processing/edit_day.php">
        <div id="basic_inputs">
            <input type="hidden" name="day_id" value="<?=$_GET['day_id'] ?? ""?>">
            <div class="form-group">
                <label for="quota">Quota du jour :</label>
                <input type="number" class="form-control" name="quota" id="quota" required value="<?= $day->quota ?? '' ?>">
            </div>
            <div class="form-group">
                <label for="reservation_opening_date">Ouverture des réservations :</label>
                <div class="input-group date">
                    <input autotocomplete='off' placeholder="jj/mm/aaaa --:--" type="text" class="form-control" name="reservation_opening_date" id="reservation_opening_date" required value="<?= $day->reservation_opening_date ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="reservation_first_closure_date">Fermeture des premières réservations :</label>
                <div class="input-group date">
                    <input autotocomplete='off' placeholder="jj/mm/aaaa --:--" type="text" class="form-control" name="reservation_first_closure_date" id="reservation_first_closure_date" required value="<?= $day->reservation_first_closure_date ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="reservation_second_closure_date">Fermeture des secondes réservations :</label>
                <div class="input-group date">
                    <input autotocomplete='off' placeholder="jj/mm/aaaa --:--" type="text" class="form-control" name="reservation_second_closure_date" id="reservation_second_closure_date" required value="<?= $day->reservation_second_closure_date ?? '' ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="pickup_date">Récupération des sandwichs :</label>
                <div class="input-group date">
                    <input placeholder="jj/mm/aaaa --:--" type="text" class="form-control" name="pickup_date" id="pickup_date" required value="<?= $day->pickup_date ?? '' ?>">
                </div>
            </div>
        </div>
        <input type="hidden" name="sandwiches">

        <h2>Sandwichs du jour</h2>
        <table id="sandwich_table" class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Nom</th>
                    <th scope="col" class="text-center">Quota</th>
                    <th scope="col" class="text-center">Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($day_sandwiches as $sandwich) {
                    $special_or_classic = $sandwich['is_special'] ? 'Spécial' : 'Classique'; ?>
                    <tr class='<?=$sandwich['is_removed'] ? "deleted" : "displayed"?>' data-sandwich_id="<?=$sandwich['sandwich_id']?>">
                        <td class="text-center"><?=$sandwich['name'] . ' (' . $special_or_classic . ')'?></td>
                        <td class="text-center"><span class="quota"><?=$sandwich['quota'] ?? $sandwich['default_quota']?></span> <button type="button" class="edit_quota btn btn-primary btn-sm"><span class="oi oi-pencil"></span></button></td>
                        <td class="text-center"><button class="btn btn-<?=$sandwich['is_removed'] ? "success restore" : "danger delete"?>" type="button"><?=$sandwich['is_removed'] ? "Restaurer le sandwich" : "Supprimer le sandwich"?></button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button class="btn btn-primary" type="submit">Envoyer</button>
    </form>
</div>
<script src="js/basic.js"></script>
<script src="js/edit_day.js"></script>