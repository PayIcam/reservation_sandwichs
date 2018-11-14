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