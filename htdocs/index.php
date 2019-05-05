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

if ((!preg_match('#^/$#', $uri))&&(!preg_match('#^/deloge/register#', $uri))&&(!preg_match('#^/benevole/register#', $uri))&&(!preg_match('#^/register#', $uri))&&(!preg_match('#^/lostpassword#', $uri))&&(!preg_match('#^/validation#', $uri))) {
    if (!isset($_SESSION['user_id']))
    header("Location: /");
}


if (preg_match('#^/$#', $uri)) {
    include( AOUF_BASE . '../tpl/index.php');
} elseif (preg_match('#^/accueil#', $uri)) {
    include( AOUF_BASE . '../tpl/accueil.php');
} elseif (preg_match('#^/lostpassword#', $uri)) {
    include( AOUF_BASE . '../tpl/lostpassword.php');
} elseif (preg_match('#^/offer/new#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_new.php');
} elseif (preg_match('#^/offer/list#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_list.php');
} elseif (preg_match('#^/offer/mylist#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_mylist.php');
} elseif (preg_match('#^/offer/edit#', $uri)) {
    include( AOUF_BASE . '../tpl/offer_edit.php');
} elseif (preg_match('#^/register#', $uri)) {
    include( AOUF_BASE . '../tpl/register.php');
} elseif (preg_match('#^/validation#', $uri)) {
    include( AOUF_BASE . '../tpl/validation.php');
} elseif (preg_match('#^/message/list#', $uri)) {
    include( AOUF_BASE . '../tpl/message_list.php');
} elseif (preg_match('#^/message/write#', $uri)) {
    include( AOUF_BASE . '../tpl/message_write.php');
} elseif (preg_match('#^/parametres#', $uri)) {
    include( AOUF_BASE . '../tpl/parametres.php');
} elseif (preg_match('#^/admin/register#', $uri)) {
    include( AOUF_BASE . '../tpl/admin_register.php');
} elseif (preg_match('#^/benevole/register#', $uri)) {
    include( AOUF_BASE . '../tpl/benevole_register.php');
} elseif (preg_match('#^/deloge/register#', $uri)) {
    include( AOUF_BASE . '../tpl/deloge_register.php');
} elseif (preg_match('#^/admin/moderation#', $uri)) {
    include( AOUF_BASE . '../tpl/admin_moderation.php');
} else {
    echo '404: NOT FOUND';
}

?>
