$(document).ready(function() {
    $('#reservation_opening_date').datetimepicker({
        sideBySide: true
    });
    $('#reservation_closure_date').datetimepicker({
        sideBySide: true,
        useCurrent: false //Important! See issue #1075
    });
    $('#pickup_date').datetimepicker({
        sideBySide: true,
        useCurrent: false //Important! See issue #1075
    });

    $("#reservation_opening_date").on("dp.change", function (e) {
        $('#reservation_closure_date').data("DateTimePicker").minDate(e.date);
    });
    $("#reservation_closure_date").on("dp.change", function (e) {
        $('#reservation_opening_date').data("DateTimePicker").maxDate(e.date);
        $('#pickup_date').data("DateTimePicker").minDate(e.date);
    });
    $("#pickup_date").on("dp.change", function (e) {
        $('#reservation_closure_date').data("DateTimePicker").maxDate(e.date);
    });

    function change_quota() {
        quota = $(this).prev('span').text();
        input = '<input type="number" class="form-control" value="' + quota + '">'
        button = '<button type="button" class="btn btn-primary btn-sm"><span class="oi oi-check"></span></button>'
        cell = $(this).parent().html(input + button);
        cell.children('button').click(function() {
            quota = '<span class="quota">' +  $(this).prev('input').val() + '</span>';
            button = ' <button type="button" class="btn btn-primary btn-sm edit_quota"><span class="oi oi-pencil"></span></button>'
            cell = $(this).parent().html(quota + button);
            cell.children('button').click(change_quota);
        });
    }

    $('#sandwich_table .delete').click(function() {
        if(window.confirm("Voulez vous vraiment supprimer ce sandwich ?")) {
            $(this).parents('tr').remove();
        }
    });

    // <td class="text-center"><?=$sandwich['default_quota']?> <button type="button" class="edit_quota btn btn-primary"><span class="oi oi-pencil text-right"></span></button></td>

    $('.edit_quota').click(change_quota);

    $('form').submit(function(submit) {
        var sandwiches = [];
        $('#sandwich_table tbody tr').each(function() {
            var sandwich = {sandwich_id: $(this).data('sandwich_id'), quota: $(this).find('.quota').text()}
            sandwiches.push(sandwich);
        });
        $('input[name=sandwiches]').val(JSON.stringify(sandwiches));
    });
});