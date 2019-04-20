<?php

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

?>

<h2>Liste de mes offres</h2>
<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * FROM offers WHERE user_id = $user_id";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

    $offer_id = $data['id'];
    $titre = $data['title'];
    $description = $data['description'];
    
    echo "Annonce $offer_id : $titre - $description : <a href='/offer/suppr/$offer_id'>Désactiver cette offre</a><br>";

}

?>


