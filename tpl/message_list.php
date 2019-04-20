<?php

$user_id = $_SESSION['user_id'];

?>

<h2>Liste des messages</h2>

<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT DISTINCT offer_id FROM messages WHERE from_id=$user_id OR to_id=$user_id";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$offer_id = $data['offer_id'];

echo "Annonce $offer_id : <a href='/message/write/$offer_id'>Voir messages</a><br>";

}

?>


