<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/offer/list/(\w+)$#', $uri, $matches);
$offer_category = trim($matches[1]);
?>
<div class="container bg-blanc noir full-size">
    <h2>Liste des offres</h2>
    <?php
        $pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
        $req = "SELECT * FROM offers WHERE category = '$offer_category' AND status='enabled'";
        $statement = $pdo->query($req);

        while ($data = $statement->fetch()) {

            $offer_id = $data['id'];
            $titre = $data['title'];
            $description = $data['description'];
            
            echo "Annonce $offer_id : $titre - $description : <a href='/message/write/$offer_id/$user_id'>Réserver cette offre</a><br>";

        }
    ?>
</div>
<?php
    require_once 'footer.php';
?>
