<div class="container">
    <h1 class="text-center">Administration des sandwichs disponibles à la vente</h1>
    <h2 class="text-center">Liste des sandwichs déjà disponibles</h2>
    <div id="ajax_alerts"></div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Quota par défaut</th>
                <th scope="col">Description</th>
                <th scope="col">Editer</th>
                <th scope="col">Supprimer/Restaurer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sandwiches as $sandwich) {?>
                <tr>
                    <th><?=$sandwich['name']?></th>
                    <th><?=$sandwich['default_quota']?></th>
                    <th><?=$sandwich['description']?></th>
                    <th><a href="edit_sandwich.php?sandwich_id=<?=$sandwich['sandwich_id']?>" class="btn btn-primary" role="button">Editer</a></th>
                    <th>
                        <?php if($sandwich['is_removed'] == 0) {
                            echo "<button data-sandwich_id=" . $sandwich['sandwich_id'] . " class='btn btn-danger delete_sandwich'>Supprimer le sandwich</button>";} else {
                            echo "<button data-sandwich_id=" . $sandwich['sandwich_id'] . " class='btn btn-success restore_sandwich'>Restaurer le sandwich</button>"; } ?>
                    </th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script src="js/admin_sandwichs.js"></script>
