<?php

session_start();

require 'vendor/autoload.php';
$_CONFIG = require 'config.php';

function getPayutcClient($service)
{
    global $_CONFIG;
    return new \JsonClient\AutoJsonClient(
        $_CONFIG['payicam']['url'],
        $service,
        array(),
        "PayIcam Json PHP Client",
        $_SESSION['payutc_cookie'] ?? "");
}

$Auth = new \CoreHelpers\Auth();

$route = str_replace($_CONFIG['base_path'], '', $_SERVER['REQUEST_URI']);
$route = current(explode('?', $route, 2));

$payutcClient = getPayutcClient("WEBSALE");

$status = $payutcClient->getStatus();
$is_super_admin = $payutcClient->isSuperAdmin();
$is_admin = $payutcClient->isAdmin();

$casUrl = $_CONFIG['cas_url']."login?service=".urlencode($_CONFIG['public_url']."login.php");
$logoutUrl = $_CONFIG['cas_url']."logout?service=".urlencode($_CONFIG['public_url']."login.php");

$icam_informations = null;


if(!in_array($route, ['login.php', 'callback.php'])) {

    if((!isset($status) || !$status->user))// Il n'était pas encore connecté en tant qu'icam.
    {
        header('Location:'.$casUrl, true, 303); die();
    }
    if (!empty($status->user)) {
        $has_cafet_admin_rights = false;
        $has_cafet_rights = false;
        $is_in_cafet_page = !in_array($route, ['homepage.php', 'processing/reservation.php', 'processing/cancel_reservation.php']);
        $is_in_cafet_admin_page = !in_array($route, ['homepage.php', 'processing/reservation.php', 'processing/cancel_reservation.php', 'admin_homepage.php', 'admin_view.php', 'add_reservation.php', 'processing/toggle_pickup.php', 'processing/toggle_pickup.php', 'processing/toggle_pickup.php', 'processing/add_reservation.php', 'processing/autocomplete.php']);


        try {
            $has_cafet_rights = \CoreHelpers\Auth::has_payicam_rights($_CONFIG['cafet_fun_id'], 'getPayutcClient', 'POSS3');
        } catch(JsonClient\JsonException $e) {
            if($is_in_cafet_page) {
                header('Location: '.$_CONFIG['public_url']);
                die();
            }
        }
        try {
            $has_cafet_admin_rights = \CoreHelpers\Auth::has_payicam_rights($_CONFIG['cafet_fun_id'], 'getPayutcClient', 'ADMINRIGHT');
        } catch(JsonClient\JsonException $e) {
            if($is_in_cafet_admin_page) {
                header('Location: '.$_CONFIG['public_url']);
                die();
            }
        }

        if(!$has_cafet_admin_rights) {
            if($is_in_cafet_admin_page) {
                header('Location: '.$_CONFIG['public_url']);
                die();
            } elseif(!$has_cafet_rights) {
                if($is_in_cafet_page) {
                    header('Location: '.$_CONFIG['public_url']);
                    die();
                }
            }
        }

        if (empty($status->application) || isset($status->application->app_url) && strpos($status->application->app_url, 'bar_trader') === false)// il était connecté en tant qu'icam mais l'appli non
        {
            try {
                $payutcClient->loginApp(array("key"=>$_CONFIG['payicam']['key']));
                $status = $payutcClient->getStatus();
            } catch (\JsonClient\JsonException $e) {
                // $this->flash->addMessage('info', "error login application, veuillez finir l'installation de l'app");
                header('Location:'.$casUrl, true, 303);die();
            }
        }
        // tout va bien
        $icam_informations = json_decode(file_get_contents($_CONFIG['ginger']['url'].$Auth->getUserField('email')."/?key=".$_CONFIG['ginger']['key']));
        $_SESSION['icam_informations'] = $icam_informations;

    }
}

$db = new DB($_CONFIG['database']['sql_host'], $_CONFIG['database']['sql_db'], $_CONFIG['database']['sql_login'], $_CONFIG['database']['sql_pass']);

