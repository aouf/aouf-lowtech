<?php

if (($_SESSION['login_type']!='admin')&&($_SESSION['login_type']!='deloge')) {
    die("permission denied");
}

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/annonce/list/(\w+)$#', $uri, $matches);
$annonce_type = trim($matches[1]);
?>

<h2>Liste des offres</h2>
<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * FROM offers WHERE offer_type = '$annonce_type'";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

    $annonce = $data['id'];
    $titre = $data['title'];
    $description = $data['description'];
    
    echo "Annonce $annonce : $titre - $description : <a href='/message/write/$annonce'>Réserver cette offre</a><br>";

}

?>


