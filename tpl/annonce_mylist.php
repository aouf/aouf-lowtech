<?php

if (($_SESSION['login_type']!='admin')&&($_SESSION['login_type']!='benevole')) {
    die("permission denied");
}

$login_id = $_SESSION['login_id'];

?>

<h2>Liste de mes offres</h2>
<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$req = "SELECT * FROM offers WHERE user_id = $login_id";
$statement = $pdo->query($req);

while ($data = $statement->fetch()) {

    $annonce = $data['id'];
    $titre = $data['title'];
    $description = $data['description'];
    
    echo "Annonce $annonce : $titre - $description : <a href='/annonce/suppr/$annonce'>Désactiver cette offre</a><br>";

}

?>


