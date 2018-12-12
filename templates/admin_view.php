<?php setlocale(LC_TIME, "fr_FR");?>

<div class="container">
    <div id="ajax_alerts"></div>
    <h1 class="text-center"><?=strftime("%A %e %B %Y", strtotime($day->day))?> <a href="export_day.php?day_id=<?=$_GET['day_id']?>"><button type="button" class="btn btn-primary btn-sm"><span class="oi oi-data-transfer-download"></span></button></a></h1>
    <table class="table table-hover table-bordered text-center">
        <thead>
            <tr>
                <th>Sandwichs</th>
                <th>Demis</th>
                <th>Baguettes</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($sandwiches_stats as $sandwich_stats) { ?>
            <tr>
                <th><?=$sandwich_stats['name']?></th>
                <td><?=$sandwich_stats['demis']?></td>
                <td><?=$sandwich_stats['baguettes']?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <table class="table table-hover table-bordered text-center">
        <thead>
            <tr>
                <th>Compteur</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Promo</th>
                <th>Payement</th>
                <th>Date de payement</th>
                <th>Choix</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; foreach($reservations as $reservation) { ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$reservation['firstname']?></td>
                <td><?=$reservation['lastname']?></td>
                <td><?=$reservation['promo']?></td>
                <td><?=$reservation['payement']?></td>
                <td><?=$reservation['payment_date']?></td>
                <td><?=$reservation['possibility'] . ' ' . $reservation['sandwich']?></td>
                <td><?=Reservation::display_pickup_button($reservation)?></td>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>
</div>
<script src="js/admin_view.js"></script>