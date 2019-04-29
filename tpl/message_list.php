<?php

$user_id = $_SESSION['user_id'];
require_once 'head.php';
require_once 'header.php';
?>

<h2>Liste des messages</h2>

<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT DISTINCT offer_id,from_id,to_id FROM messages WHERE from_id=$user_id OR to_id=$user_id";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$offer_id = $data['offer_id'];
$with_id = $data['from_id'];
if ($with_id == $user_id) $with_id = $data['to_id'];

echo "Offre $offer_id / message avec $with_id : <a href='/message/write/$offer_id/$with_id'>Voir messages</a><br>";

}

require_once 'header.php';
