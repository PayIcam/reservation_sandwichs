$(document).ready(function() {
    function ajax_success(data) {
        if(data.message=='Le sandwich a bien été restauré' | data.message == 'Le sandwich a bien été supprimé') {
            create_alert(data.message, 'success', 'ajax_alerts');
        } else if(data.message == 'Le sandwich a été totalement supprimé') {
            $('[data-sandwich_id="' + data.sandwich_id + '"').parents('tr').remove();
            create_alert(data.message, 'success', 'ajax_alerts');
        }
        else {
            create_alert(data.message, 'danger', 'ajax_alerts');
        }
    }

    function delete_sandwich() {
    if(window.confirm('Voulez vous vraiment supprimer ce sandwich ?')) {
        $.get(
            {
                url: "processing/toggle_sandwich.php",
                data: {sandwich_id: $(this).data('sandwich_id')},
                dataType: 'json',
                success: ajax_success,
                error: error_ajax,
            });
            $(this).removeClass('btn-danger').removeClass('delete_sandwich').addClass('btn-success').addClass('restore_sandwich').text('Restaurer le sandwich');
            $(this).off('click').click(restore_sandwich);
        }
    }
    function restore_sandwich() {
    if(window.confirm('Voulez vous vraiment restaurer ce sandwich ?')) {
        $.get(
            {
                url: "processing/toggle_sandwich.php",
                data: {sandwich_id: $(this).data('sandwich_id')},
                dataType: 'json',
                success: ajax_success,
                error: error_ajax,
            });
            $(this).removeClass('btn-success').removeClass('restore_sandwich').addClass('btn-danger').addClass('delete_sandwich').text('Supprimer le sandwich');
            $(this).off('click').click(delete_sandwich);
        }
    }

    $('.delete_sandwich').click(delete_sandwich);
    $('.restore_sandwich').click(restore_sandwich);
});