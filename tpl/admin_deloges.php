<?php

$login_id = $_SESSION['login_id'];

?>

<h2>Liste des comptes à modérer</h2>

<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * from users WHERE status='unvalidated'";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$u_id = $data['id'];
$u_login = $data['login'];
$u_nom = $data['name'];
$u_prenom = $data['firstname'];
$u_email = $data['email'];
$u_phonenumber = $data['phonenumber'];

echo "- $u_prenom $u_nom ($u_login) : $u_phonenumber / $u_email <a href='/admin/moderate/$u_id'>Activer</a><br>";

}

?>


