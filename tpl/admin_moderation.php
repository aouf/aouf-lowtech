<?php

if ($_SESSION['user_category']!='admin') {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

?>

<h2>Liste des comptes à modérer</h2>

<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * from users WHERE status='unvalidated'";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$moderation_id = $data['id'];
$moderation_login = $data['login'];
$moderation_nom = $data['name'];
$moderation_prenom = $data['firstname'];
$moderation_email = $data['email'];
$moderation_phonenumber = $data['phonenumber'];

echo "- $moderation_prenom $moderation_nom ($moderation_login) : $moderation_phonenumber / $moderation_email <a href='/admin/moderate/$moderation_id'>Activer</a><br>";

}

?>


