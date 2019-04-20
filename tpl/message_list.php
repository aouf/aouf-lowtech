<h2>Liste des messages</h2>
<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * FROM messages";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$annonce = $data['offer_id'];
$message = $data['message'];

echo "Annonce $annonce - $message : <a href='/message/write'>Voir</a><br>";

}

?>
<a href='/deloge/'>Retour</a>


