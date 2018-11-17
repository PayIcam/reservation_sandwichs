<div class="container">
    <h1 class="text-center">Réserver des sandwichs</h1>
    <h2 class="text-center">Prochains jours de réservations</h2>
    <div id="ajax_alerts"></div>
    <table class="table table-hover table-bordered text-center">
        <thead>
            <tr>
                <th>Jours</th>
                <th>Réservations</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            setlocale(LC_TIME, "fr_FR"); foreach($days as $day) { ?>
            <tr>
                <th><?=strftime("%A %e %B %Y", strtotime($day['day']))?></th>
                <td><?= !empty($day['reservation']) ? Reservation::display_reservation_name($day['reservation']['reservation_id']) : ""?></td>
                <td><?=Day::display_action_button($day, $possibilities)?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script src="js/homepage.js"></script>