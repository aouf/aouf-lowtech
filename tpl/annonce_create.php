<?php
$titre = $_POST['titre'];
$description = $_POST['description'];
$type = $_POST['type'];

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "INSERT INTO offers(user_id,offer_type,title,description) VALUES (?,?,?,?)";
$statement = $pdo->prepare($req);
$statement->execute([42, $type, $titre, $description]);

echo "Annonce $titre postée, merci !<br>";

?>
<a href='/benevole/'>Retour</a>
