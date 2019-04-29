<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')) {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

if (isset($_POST['title'])) {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $arrondissement = $_POST['arrondissement'];
    $address = $_POST['address'];
    $description = $_POST['description1'].$_POST['description2'].$_POST['description3'];
    $picture = file_get_contents($_FILES['picture']['tmp_name']);

    $req = "INSERT INTO offers(user_id,category,title,description,status,arrondissement,address,picture) VALUES (?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$user_id, $category, $title, $description,'enabled', $arrondissement, $address, $picture]);

    echo "Annonce <strong>$title</strong> postée, merci&nbsp;!<br>";
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
<div class="container bg-blanc noir full-size">
<h2>Proposer une offre</h2>

    <form method='post' enctype='multipart/form-data'>
    Titre : <input type='text' name='title'>*<br>
    Catégorie de l'offre : <?php print $category; ?><br>
    Arrondissement : <select name='arrondissement'>
    <option value='0'>-</option>
    <option value='1'>1er</option>
    <option value='2'>2eme</option>
    <option value='3'>3eme</option>
    <option value='4'>4eme</option>
    <option value='5'>5eme</option>
    <option value='6'>6eme</option>
    <option value='7'>7eme</option>
    <option value='8'>8eme</option>
    <option value='9'>9eme</option>
    <option value='10'>10eme</option>
    <option value='11'>11eme</option>
    <option value='12'>12eme</option>
    <option value='13'>13eme</option>
    <option value='14'>14eme</option>
    <option value='15'>15eme</option>
    <option value='16'>16eme</option></select>*<br>
    Adresse : <input type='text' name='address'><br>
    Début de l'offre : <input type='text'><br>
    Fin de l'offre : <input type='text'><br>
    <?php print $description ?> <br>
    <textarea name='description1'></textarea><br>
    <textarea name='description2'></textarea><br>
    <textarea name='description3'></textarea>*<br>
    Photo : <input type='file' name='picture'><br>
    <input type='hidden' name='category' value='<?php print $category; ?>'>
    <input type='submit' value='Publier'>
    </form>

</div>
<?php
require_once 'footer.php';
