<?php

// Path
define('AOUF_BASE','./');

// Configuration
if (!file_exists( AOUF_BASE . '../conf/connect.php')||(!file_exists( AOUF_BASE . '../conf/global.php'))) {
    exit('<p style="text-align: center; margin-top: 15%; ">La configuration de connexion semble incorrecte.</p>');
}
require_once AOUF_BASE . '../conf/connect.php';
require_once AOUF_BASE . '../conf/global.php';

// Session
session_name('AOUF_SESS');
session_start();

// Get URI request
$uri = $_SERVER['REQUEST_URI'];

if ((!preg_match('#^/$#', $uri))&&(!preg_match('#^/couches/register#', $uri)&&(!preg_match('#^/coordinateur/register#', $uri)&&!preg_match('#^/deloge/register#', $uri))&&(!preg_match('#^/benevole/register#', $uri))&&(!preg_match('#^/register#', $uri))&&(!preg_match('#^/lost#', $uri))&&(!preg_match('#^/validation#', $uri))&&(!preg_match('#^/cgu#', $uri))&&(!preg_match('#^/reset#', $uri)))) {
    if (!isset($_SESSION['user_id']))
    header("Location: /");
}

if (preg_match('#^/$#', $uri)) {
    include( AOUF_BASE . '../tpl/index.php');
} elseif (preg_match('#^/\?logout$#', $uri)) {
    include( AOUF_BASE . '../tpl/index.php');
} elseif (preg_match('#^/accueil#', $uri)) {
    include( AOUF_BASE . '../tpl/accueil.php');
} elseif (preg_match('#^/lostpassword#', $uri)) {
    include( AOUF_BASE . '../tpl/lostpassword.php');
} elseif (preg_match('#^/offer/new#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_new.php');
} elseif (preg_match('#^/offer/besoin#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_besoin.php');
} elseif (preg_match('#^/offer/list#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_list.php');
} elseif (preg_match('#^/offer/mylist#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_mylist.php');
} elseif (preg_match('#^/offer/yourlist#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_yourlist.php');
} elseif (preg_match('#^/offer/edit#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_edit.php');
} elseif (preg_match('#^/offer/show#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_show.php');
} elseif (preg_match('#^/register#', $uri)) {
    include( AOUF_BASE . '../tpl/register.php');
} elseif (preg_match('#^/validation#', $uri)) {
    include( AOUF_BASE . '../tpl/validation.php');
} elseif (preg_match('#^/message/list#', $uri)) {
    include( AOUF_BASE . '../tpl/message_list.php');
} elseif (preg_match('#^/message/write#', $uri)) {
    include( AOUF_BASE . '../tpl/message_write.php');
} elseif (preg_match('#^/parametres#', $uri)) {
    include( AOUF_BASE . '../tpl/parameters.php');
} elseif (preg_match('#^/admin/register#', $uri)) {
    include( AOUF_BASE . '../tpl/admin_register.php');
} elseif (preg_match('#^/benevole/register#', $uri)) {
    include( AOUF_BASE . '../tpl/benevole_register.php');
} elseif (preg_match('#^/deloge/register#', $uri)) {
    include( AOUF_BASE . '../tpl/deloge_register.php');
} elseif (preg_match('#^/coordinateur/register#', $uri)) {
    include( AOUF_BASE . '../tpl/coordinateur_register.php');
} elseif (preg_match('#^/couches/register#', $uri)) {
    include( AOUF_BASE . '../tpl/couches_register.php');
} elseif (preg_match('#^/admin/moderation#', $uri)) {
    include( AOUF_BASE . '../tpl/admin_moderation.php');
} elseif (preg_match('#^/feedback#', $uri)) {
    include( AOUF_BASE . '../tpl/feedback.php');
} elseif (preg_match('#^/whatineed#', $uri)) {
    include( AOUF_BASE . '../tpl/whatineed.php');
} elseif (preg_match('#^/report#', $uri)) {
    include( AOUF_BASE . '../tpl/report.php');
} elseif (preg_match('#^/reset#', $uri)) {
    include( AOUF_BASE . '../tpl/reset.php');
} elseif (preg_match('#^/cgu#', $uri)) {
    include( AOUF_BASE . '../tpl/cgu.php');
} elseif (preg_match('#^/lostlogin#', $uri)) {
    echo "Merci d'envoyer un email à toctoc AROBASE aouf POINT fr pour expliquer votre situation.";
} else {
    echo '404: NOT FOUND';
}

?>
