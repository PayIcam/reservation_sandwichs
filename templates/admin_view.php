<?php setlocale(LC_TIME, "fr_FR");?>

<div class="container">
    <div id="ajax_alerts"></div>
    <h1 class="text-center"><?=strftime("%A %e %B %Y", strtotime($day->day))?></h1>
    <table class="table table-hover table-bordered text-center">
        <thead>
            <tr>
                <th rowspan="2">Sandwichs</th>
                <?php foreach($possibilities as $possibility) { ?>
                    <th colspan="2"><?=$possibility?></th>
                <?php } ?>
            </tr>
            <tr>
                <?php foreach($possibilities as $possibility) { ?>
                    <th>Commandes</th>
                    <th>Récupérés</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($choices_stats as $choice_stats) { ?>
                <tr>
                <?php foreach($choice_stats as $choice_stat) {
                    $pourcentage_r = Functions::pourcentage_extended_zero_division($choice_stat['picked_ups'], $choice_stat['reservations']);?>
                    <th><?=$choice_stat['sandwich']?></th>
                    <td><?=$choice_stat['reservations']?></td>
                    <td class="<?=Functions::display_percentage_style($pourcentage_r)?>"><?=$choice_stat['picked_ups'] . ' (' . $pourcentage_r . '%)'?></td>
                <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Promo</th>
                <th>Choix</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reservations as $reservation) { ?>
            <tr>
                <td><?=$reservation['firstname']?></td>
                <td><?=$reservation['lastname']?></td>
                <td><?=$reservation['promo']?></td>
                <td><?=$reservation['possibility'] . ' ' . $reservation['sandwich']?></td>
                <td><?=Reservation::display_pickup_button($reservation)?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script src="js/admin_view.js"></script>