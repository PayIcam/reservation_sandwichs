$(document).ready(function() {
    function ajax_success(data) {
        if(data.message!='Tout a bien fonctionné') {
            create_alert(data.message, 'danger', 'ajax_alerts');
        }
    }

    function pickup() {
        $.get(
            {
                url: "processing/toggle_pickup.php",
                data: {reservation_id: $(this).data('reservation_id')},
                dataType: 'json',
                success: ajax_success,
                error: error_ajax,
            });
            $(this).removeClass('btn-success').removeClass('pickup').addClass('btn-danger').addClass('unpickup').children('span').removeClass('oi-check').addClass('oi-x');
            $(this).off('click').click(unpickup);
    }
    function unpickup() {
        $.get(
            {
                url: "processing/toggle_pickup.php",
                data: {reservation_id: $(this).data('reservation_id')},
                dataType: 'json',
                success: ajax_success,
                error: error_ajax,
            });
            $(this).removeClass('btn-danger').removeClass('unpickup').addClass('btn-success').addClass('pickup').children('span').removeClass('oi-x').addClass('oi-check');
            $(this).off('click').click(pickup);
    }

    $('.pickup').click(pickup);
    $('.unpickup').click(unpickup);
});