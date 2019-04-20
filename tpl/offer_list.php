<?php

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/offer/list/(\w+)$#', $uri, $matches);
$offer_category = trim($matches[1]);
?>

<h2>Liste des offres</h2>
<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * FROM offers WHERE category = '$offer_category'";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

    $offer_id = $data['id'];
    $titre = $data['title'];
    $description = $data['description'];
    
    echo "Annonce $offer_id : $titre - $description : <a href='/message/write/$offer_id'>Réserver cette offre</a><br>";

}

?>


