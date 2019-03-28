<div class="container-fluid">
    <h1 class="text-center">Prochains jours de ventes de sandwichs</h1>
    <table class="table table-hover table-bordered text-center table-sm">
        <thead>
            <tr>
                <th rowspan="2">Jours</th>
                <th colspan="2">Total</th>
                <?php foreach($sandwiches as $sandwich) { ?>
                    <th colspan="2"><?=$sandwich['name']?></th>
                <?php } ?>
            </tr>
            <tr>
                <?php foreach($sandwiches as $sandwich) { ?>
                    <th>Commandes</th>
                    <th>Récupérés</th>
                <?php } ?>
                <th>Commandes</th>
                <th>Récupérés</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($days_stats as &$day_stats) { ?>
            <tr>
                <th>
                    <?php setlocale(LC_TIME, "fr_FR");
                    $day_stats['current_quota'] = $day_stats['pendings'] + $day_stats['reservations']; ?>
                    <?=strftime("%A %e %B %Y", strtotime($day_stats['day']))?>
                    <a href="admin_view.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm" title="Voir les réservations"><span class="oi oi-eye"></span></a>
                    <?php if(Day::can_book_sandwiches($day_stats, false)) { ?>
                        <a href="add_reservation.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm" title="Ajouter une réservation"><span class="oi oi-plus"></span></a>
                    <?php }
                    if($has_cafet_admin_rights) { ?>
                        <a href="edit_day.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm" title="Editer le jour de réservations"><span class="oi oi-pencil"></span></a>
                        <a href="export_day.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm" title="Télécharger un export"><span class="oi oi-data-transfer-download"></span></a>
                        <?php if(!Day::cant_remove_day($day_stats['day_id'])): ?> 
                            <a href="processing/delete_day.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm" title="Supprimer un jour" onclick='return confirm("Voulez vous vraiment supprimer le jour ?")'><span style="color: red;" class="oi oi-trash"></span></a>
                        <?php endif; ?>
                    <?php } ?>
                </th> <?php
                $pourcentage_r = Functions::pourcentage_extended_zero_division($day_stats['reservations'], $day_stats['quota']);
                $pourcentage_p = Functions::pourcentage_extended_zero_division($day_stats['picked_ups'], $day_stats['reservations']); ?>

                <td class="<?=Functions::display_percentage_style($pourcentage_r)?>"><?=$day_stats['reservations'] . '/' . $day_stats['quota'] . ' (' . $pourcentage_r . '%)'?></td>
                <td class="<?=Functions::display_percentage_style($pourcentage_p)?>"><?=$day_stats['picked_ups'] . '/' . $day_stats['reservations'] . ' (' . $pourcentage_p . '%)'?></td>
                <?php foreach($day_stats['sandwiches_stats'] as $sandwich_stats) {
                    $pourcentage_r = Functions::pourcentage_extended_zero_division($sandwich_stats['reservations'], $sandwich_stats['quota']);
                    $pourcentage_p = Functions::pourcentage_extended_zero_division($sandwich_stats['picked_ups'], $sandwich_stats['reservations']); ?>
                    <td class="<?=Functions::display_percentage_style($pourcentage_r)?>"><?=$sandwich_stats['reservations'] . '/' . $sandwich_stats['quota'] . ' (' . $pourcentage_r . '%)'?></td>
                    <td class="<?=Functions::display_percentage_style($pourcentage_p)?>"><?=$sandwich_stats['picked_ups'] . '/' . $sandwich_stats['reservations'] . ' (' . $pourcentage_p . '%)'?></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>