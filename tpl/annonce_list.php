<h2>Liste des offres</h2>
<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * FROM offers";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

$titre = $data['title'];
$description = $data['description'];

echo "$titre - $description : <a href='#'>Réserver cette offre</a><br>";

}

?>
<a href='/deloge/'>Retour</a>


