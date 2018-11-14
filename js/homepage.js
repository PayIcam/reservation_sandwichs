$(document).ready(function() {
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus');
    });
    $(function () {
        $('[data-toggle="popover"]').popover()
    });

    console.log($('button:not(.button_disabled)'));

    function ajax_success(data) {
        if(data.message == 'Sandwich commandé !') {
            create_alert(data.message, 'success', 'ajax_alerts');
            document.location.href = data.url;
        } else {
            create_alert(data.message, 'danger', 'ajax_alerts');
            $('button:not(.button_disabled)').removeAttr('disabled');
        }
    }

    $('.reservation').click(function() {
        var possibility_id = $(this).data('possibility_id');
        var sandwich_id = $(this).parents('tr').data('sandwich_id');
        var day_id = $(this).parents('div[data-day_id]').data('day_id');
        $.get(
            {
                url: "processing/reservation.php",
                data: {day_id: day_id, sandwich_id: sandwich_id, possibility_id: possibility_id},
                dataType: 'json',
                success: ajax_success,
                error: error_ajax,
            });
        $('button:not(.button_disabled)').attr('disabled', 'disabled');
    });
});