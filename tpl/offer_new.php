<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

if (isset($_POST['titre'])) {
    $titre = $_POST['titre'];
    $adresse = $_POST['adresse'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    $req = "INSERT INTO offers(user_id,category,title,description) VALUES (?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$user_id, $category, $titre, $description]);

    echo "Annonce <strong>$titre</strong> postée, merci&nbsp;!<br>";
}

$uri = $_SERVER['REQUEST_URI'];
$description = "Décrivez ce que vous proposez.";
if (preg_match('#^/offer/new/restauration#', $uri)) {
    $category = 'restauration';
    $description = "Décrivez ce que vous proposez (pour combien de personnes ?
sans porc/végétarien/végétalien/hallal/casher/sans gluten ?
sur place ou à emporter ? etc.)
";
} elseif (preg_match('#^/offer/new/blanchisserie#', $uri)) {
    $category = 'blanchisserie';
    $description = "Décrivez ce que vous proposez (lessive fournie ?
séchage sur place ? etc.)
";
} elseif (preg_match('#^/offer/new/mobilite#', $uri)) {
    $category = 'mobilite';
    $description = "Décrivez ce que vous proposez (temps disponible ?
place dans votre véhicule ? etc.)
";
} elseif (preg_match('#^/offer/new/loisir#', $uri)) {
    $category = 'loisir';
    $description = "Décrivez ce que vous proposez (activité ? pour qui ?
où ? nombre de places ? etc.)
";
} elseif (preg_match('#^/offer/new/don#', $uri)) {
    $category = 'don';
    $description = "Décrivez ce que vous donnez (taille ? poids ?
où le récupérez ? etc.)
";
} 
?>

<h2>Proposer une offre</h2>

<form action='/offer/new' method='post'>
Titre : <input type='text' name='titre'>*<br>
Catégorie de l'offre : <?php print $category; ?><br>
Adresse : <input type='text' name='adresse'><br>
Désactivation de l'offer le : <input type='text'><br>
<?php print $description ?> <br>
<textarea name='description'></textarea>*
<br><br>
<input type='hidden' name='category' value='<?php print $category; ?>'>
<input type='submit' value='Publier'>
</form>

<br>
<a href='/accueil'>Accueil</a><br>
<a href='/offer/mylist'>Mes offres</a><br>
<a href='/message/list'>Messagerie</a><br>
<a href='/parametres'>Mes paramètres</a><br>
<a href='/auth'>Déconnexion</a><br>
