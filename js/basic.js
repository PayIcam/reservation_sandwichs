function create_alert(message, type, id) {
    var alert = '<div class="alert alert-' + type + ' alert-dismissible">' + '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + message + '</div>';
    $("#" + id).append(alert);
}

function error_ajax(jqXHR, textStatus, errorThrown) {
    //S'il y a une erreur Ajax, on met dans la console les erreurs rencontrées, et on affiche un message d'erreur, disant que l'Ajax a échoué
    console.log(jqXHR);
    console.log();
    console.log(textStatus);
    console.log();
    console.log(errorThrown);
    create_alert('La requête Ajax permettant de submit les informations et supprimer le sandwich a échoué', 'danger', 'ajax_alerts');
}