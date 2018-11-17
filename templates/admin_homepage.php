<div class="container-fluid">
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
                    <?php setlocale(LC_TIME, "fr_FR"); ?>
                    <?=strftime("%A %e %B %Y", strtotime($day_stats['day']))?>
                    <a href="admin_view.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm"><span class="oi oi-eye"></span></a>
                    <?php if($has_cafet_admin_rights) { ?>
                        <a href="edit_day.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm"><span class="oi oi-pencil"></span></a>
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