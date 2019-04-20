<?php

$login_id = $_SESSION['login_id'];

?>

<h2>Liste des messages</h2>

<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT DISTINCT offer_id FROM messages WHERE from_id=$login_id OR to_id=$login_id";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$annonce = $data['offer_id'];

echo "Annonce $annonce : <a href='/message/write/$annonce'>Voir messages</a><br>";

}

?>


