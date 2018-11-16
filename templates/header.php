<!DOCTYPE html>
<html>
<head>
    <title>Admin Sandwichs</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <link href="iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    <link href="iconic/font/css/open-iconic-bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/bootstrap/bootstrap.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"> </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="js/basic.js"></script>
</head>
<body>
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid" style='width:80%'>
            <a class="navbar-brand" href="#"><img src="img/PayIcam-h30-white.png" width="100" height="33" class="d-inline-block align-top" alt=""></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="..">Accueil PayIcam</a></li>
                    <?php if($has_cafet_rights) { ?>
                    <li class="nav-item"><a class="nav-link" href="<?=$_CONFIG['public_url']?>">Accueil</a></li>
                    <!-- <li class="nav-item"><a  class="nav-link" href="about.php">À propos</a> </li> -->
                    <li class="nav-item dropdown" >
                        <a  class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Administration</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a  class="dropdown-item" href="admin_homepage.php">Accueil de l'administration</a>
                            <a  class="dropdown-item" href="admin_general_settings">Editer les paramètres généraux</a>
                            <a  class="dropdown-item" href="edit_day.php">Ajouter un jour</a>
                        </div>
                    </li>
                    <?php } ?>
                </ul>

                <ul class="nav navbar-nav my-2 my-lg-0">
                    <li class="nav-item"><a  class="nav-link" href="logout.php">Déconnexion</a></li>
                </ul>

            </div>
        </div>
    </nav>
    <div style="margin-top:4%"></div>