<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')) {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/offer/edit/(\d+)$#', $uri, $matches);
$offer_id = (int)$matches[1];
if (!($offer_id>0)) {
    print "Erreur, offre inexistante";
    die();
}

if (isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $req = "UPDATE offers SET title='$title',description='$description',status='$status' WHERE id=$offer_id";
    $statement = $pdo->prepare($req);
    $statement->execute();
}

?>

<h2>Edition offre</h2>

<?php

$req = "SELECT * FROM offers where id = $offer_id LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();
$offer_title = $data['title'];
$offer_category = $data['category'];
$offer_description = $data['description'];
$offer_description = $data['description'];
$offer_status = $data['status'];
$offer_userid = $data['user_id'];

?>

<form method='post'>
<input type='radio' name='status' value='enabled' <?php if ($offer_status == 'enabled') print "checked"; ?>>Offre active
<input type='radio' name='status' value='disabled' <?php if ($offer_status == 'disabled') print "checked"; ?>>Offre inactive<br>
Titre : <input type='text' name='title' value='<?php print $offer_title; ?>'>*<br>
Catégorie de l'offre : <?php print $offer_category; ?><br>
Adresse : <input type='text' name='address'><br>
Désactivation de l'offre le : <input type='text'><br>
<textarea name='description'><?php print $offer_description; ?></textarea>
<br><br>
<input type='submit' value='Modifier'>
</form>

<br>
<a href='/accueil'>Accueil</a><br>
<a href='/offer/mylist'>Mes offres</a><br>
<a href='/message/list'>Messagerie</a><br>
<a href='/parametres'>Mes paramètres</a><br>
<a href='/auth'>Déconnexion</a><br>

<?php
require_once 'footer.php';
