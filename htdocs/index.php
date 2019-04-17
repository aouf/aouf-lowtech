<?php

// Path
define('AOUF_BASE','./');
if (!file_exists( AOUF_BASE . '../conf/connect.php'))
{
    exit('<p style="text-align: center; margin-top: 15%; ">La configuration de connexion semble incorrecte.</p>');
}
require_once AOUF_BASE . '../conf/connect.php';
//require_once AOUF_BASE . '../conf/global.php';

// Get URI request
$uri = $_SERVER['REQUEST_URI'];

if (preg_match('#^/$#', $uri)) {
    include( AOUF_BASE . '../tpl/index.php');
} elseif (preg_match('#^/admin#', $uri)) {
    echo 'ACCES ADMIN (TODO)';
} elseif (preg_match('#^/deloge/#', $uri)) {
    include( AOUF_BASE . '../tpl/deloge.php');
} elseif (preg_match('#^/benevole/#', $uri)) {
    include( AOUF_BASE . '../tpl/benevole.php');
} elseif (preg_match('#^/annonce/new#', $uri)) {
    include( AOUF_BASE . '../tpl/annonce_new.php');
} elseif (preg_match('#^/annonce/create#', $uri)) {
    include( AOUF_BASE . '../tpl/annonce_create.php');
} elseif (preg_match('#^/annonce/list#', $uri)) {
    include( AOUF_BASE . '../tpl/annonce_list.php');
} elseif (preg_match('#^/register#', $uri)) {
    include( AOUF_BASE . '../tpl/register.php');
} else {
    echo '404: NOT FOUND';
}

?>
