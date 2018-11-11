<div class="container">
    <h1 class="text-center">Editer la config</h1>
    <form method='POST' action="processing/edit_config.php">
        <div class="form-group">
            <label for="days_displayed">Jours affichés en avance</label>
            <input type="number" class="form-control" name="days_displayed" id="days_displayed" required value="<?= $config->days_displayed ?>">
        </div>
        <div class="form-group">
            <label for="default_quota">Nombre maximum de sandwichs commandé par jour par défaut </label>
            <input type="number" class="form-control" name="default_quota" id="default_quota" required value="<?= $config->default_quota ?>">
        </div>
        <div class="form-group">
            <label for="default_reservation_closure_time">Heure de fermeture des réservations de base</label>
            <input type="time" class="form-control" name="default_reservation_closure_time" id="default_reservation_closure_time" value="<?= $config->default_reservation_closure_time ?>">
        </div>
        <div class="form-group">
            <label for="default_pickup_time">Heure de récupération des sandwichs de base</label>
            <input type="time" class="form-control" name="default_pickup_time" id="default_pickup_time" value="<?= $config->default_pickup_time ?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>