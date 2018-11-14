<div class="container-fluid">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Jours</th>
                <?php foreach($sandwiches as $sandwich) { ?>
                    <th><?=$sandwich['name']?></th>
                <?php } ?>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($days_stats as &$day_stats) { ?>
            <tr>
                <th>
                    <?=strftime("%A %e %B %Y", strtotime($day_stats['day']))?>
                    <a href="admin_view.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm"><span class="oi oi-eye"></span></a>
                    <a href="edit_day.php?day_id=<?=$day_stats['day_id']?>" role="button" class="btn btn-sm"><span class="oi oi-pencil"></span></a>
                </th>
                <?php foreach($day_stats['sandwiches_stats'] as $sandwich_stats) { ?>
                    <th><?=$sandwich_stats['reservations']?></th>
                <?php } ?>
                <th><?=$day_stats['reservations']?></th>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>