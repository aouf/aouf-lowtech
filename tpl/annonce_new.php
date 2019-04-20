<?php
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$login_id = $_SESSION['login_id'];

if (isset($_POST['titre'])) {
    $titre = $_POST['titre'];
    $adresse = $_POST['adresse'];
    $description = $_POST['description'];
    $type = $_POST['type'];

    $req = "INSERT INTO offers(user_id,offer_type,title,description) VALUES (?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$login_id, $type, $titre, $description]);

    echo "Annonce <strong>$titre</strong> postée, merci&nbsp;!<br>";
}

$uri = $_SERVER['REQUEST_URI'];
$description = "Décrivez ce que vous proposez.";
if (preg_match('#^/annonce/new/restauration#', $uri)) {
    $type = 'restauration';
    $description = "Décrivez ce que vous proposez (pour combien de personnes ?
sans porc/végétarien/végétalien/hallal/casher/sans gluten ?
sur place ou à emporter ? etc.)
";
} elseif (preg_match('#^/annonce/new/blanchisserie#', $uri)) {
    $type = 'blanchisserie';
    $description = "Décrivez ce que vous proposez (lessive fournie ?
séchage sur place ? etc.)
";
} elseif (preg_match('#^/annonce/new/mobilite#', $uri)) {
    $type = 'mobilite';
    $description = "Décrivez ce que vous proposez (temps disponible ?
place dans votre véhicule ? etc.)
";
} elseif (preg_match('#^/annonce/new/loisir#', $uri)) {
    $type = 'loisir';
    $description = "Décrivez ce que vous proposez (activité ? pour qui ?
où ? nombre de places ? etc.)
";
} elseif (preg_match('#^/annonce/new/don#', $uri)) {
    $type = 'don';
    $description = "Décrivez ce que vous donnez (taille ? poids ?
où le récupérez ? etc.)
";
} 
?>

<h2>Proposer une annonce</h2>

<form action='/annonce/new' method='post'>
Titre : <input type='text' name='titre'>*<br>
Type de l'annonce : <?php print $type; ?><br>
Adresse : <input type='text' name='adresse'><br>
Désactivation de l'annonce le : <input type='text'><br>
<?php print $description ?> <br>
<textarea name='description'></textarea>*
<br><br>
<input type='hidden' name='type' value='<?php print $type; ?>'>
<input type='submit' value='Publier'>
</form>

<br>
<a href='/accueil'>Accueil</a><br>
<a href='/annonce/mylist'>Mes offres</a><br>
<a href='/message/list'>Messagerie</a><br>
<a href='/parametres'>Mes paramètres</a><br>
<a href='/auth'>Déconnexion</a><br>
