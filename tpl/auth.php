<?php

session_unset('AOUF_SESS');
session_destroy();
session_name('AOUF_SESS');
session_start();

if (!isset($_POST['login'])) {
    header("Location: /");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$login = $_POST['login'];
$password = $_POST['password'];

$req = "SELECT id,user_type,password,status FROM users where login='$login' LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();

$hash_password = $data['password'];
$type = $data['user_type'];
$status = $data['status'];
$login_id = $data['id'];

if (!password_verify($password,$hash_password)) die("password error");
if ($status != 'enabled') die("compte non actif");

$_SESSION['login'] = $login;
$_SESSION['login_id'] = $login_id;
$_SESSION['login_type'] = $type;

header("Location: accueil");

?>
