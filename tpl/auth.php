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

$req = "SELECT id,category,password,status FROM users where login='$login' LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();

$hash_password = $data['password'];
$category = $data['category'];
$status = $data['status'];
$user_id = $data['id'];

if (!password_verify($password,$hash_password)) die("password error");
if ($status != 'enabled') die("compte non actif");

$_SESSION['user_login'] = $login;
$_SESSION['user_id'] = $user_id;
$_SESSION['user_category'] = $category;

header("Location: accueil");

?>
