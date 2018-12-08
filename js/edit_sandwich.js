$(document).ready(function() {
    $('#is_special').change(function() {
        if($(this).prop('checked') == true) {
            $('#closure_type').prop('checked', true);
            $('#closure_type').click(function() {
                return false;
            })
        } else {
            $('#closure_type').prop('checked', false);
            $('#closure_type').unbind('click');
        }
    });
});