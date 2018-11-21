$(document).ready(function() {
    console.log($('.typeahead-user'));
    $('.typeahead-user').typeahead({
        source: function (query, process) {
            return $.get('processing/autocomplete.php', { query: query, dataType: 'json' }, function (data) {
                map = {};
                usernames = [];

                $.each(JSON.parse(data), function (i, user) {
                    map[user.name + ' (' + user.mail + ')'] = user;
                    usernames.push(user.name + ' (' + user.mail + ')');
                });
                process(usernames);

                return process(usernames);
            });
        },
        updater: function(display) {
            user = map[display];
            $('input[name=firstname]').val(user.firstname).attr('readonly', '');
            $('input[name=lastname]').val(user.lastname).attr('readonly', '');
            $('input[name=email]').val(user.mail).attr('readonly', '');
            $('select[name=promo] > option').each(function() {
                if($(this).text() == user.promo) {
                    $('input[name=promo]').attr('type', 'text').attr('readonly', '').val($(this).val());
                    $(this).parent().hide().attr('disabled', '');
                    return false;
                }
            });

            $('.typeahead-user').val('');
            return user;
        }
    });
});