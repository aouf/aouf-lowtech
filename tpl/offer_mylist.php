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
    $status = $data['status'];
    $status_text = "";
    if ($status == 'disabled') $status_text = "(désactivée)";
    
    echo "Offre $offer_id $status_text : $titre - $description : <a href='/offer/edit/$offer_id'>Éditer cette offre</a><br>";

}

?>

<br>
<a href='/accueil'>Accueil</a><br>
<a href='/message/list'>Messagerie</a><br>
<a href='/parametres'>Mes paramètres</a><br>
<a href='/auth'>Déconnexion</a><br>
