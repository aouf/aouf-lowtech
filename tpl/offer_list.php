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

$arrondissement = $_SESSION['user_arrondissement'];
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
?>
<div class="container bg-blanc noir full-size">
    <h2>Liste des offres de <?php echo $offer_category; ?></h2>

    <?php
    if ($arrondissement != '') {
        print "<h3>Offres dans votre arrondissement ($arrondissement)</h3>";
        $req = "SELECT offers.id,offers.title,offers.description,offers.arrondissement,users.name,users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.category = '$offer_category' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement=$arrondissement";
        $statement = $pdo->query($req);

        while ($data = $statement->fetch()) {

            $offer_id = $data['id'];
            $titre = $data['title'];
            $description = $data['description'];
            $arrondissement = $data['arrondissement'];
            $name = $data['name'];
            $firstname = $data['firstname'];
            
            echo "Annonce $offer_id : $titre - desc: $description - from: $firstname $name - arr: $arrondissement : <a href='/message/write/$offer_id/$user_id'>Réserver cette offre</a><br>";
        }
        print "<h3>Offres dans les autres arrondissements</h3>";
        $req = "SELECT offers.id,offers.title,offers.description,offers.arrondissement,users.name,users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.category = '$offer_category' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement!=$arrondissement";
        $statement = $pdo->query($req);

        while ($data = $statement->fetch()) {

            $offer_id = $data['id'];
            $titre = $data['title'];
            $description = $data['description'];
            $arrondissement = $data['arrondissement'];
            $name = $data['name'];
            $firstname = $data['firstname'];
            
            echo "Annonce $offer_id : $titre - desc: $description - from: $firstname $name - arr: $arrondissement : <a href='/message/write/$offer_id/$user_id'>Réserver cette offre</a><br>";

        }
    } else {

        $req = "SELECT offers.id,offers.title,offers.description,offers.arrondissement,users.name,users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.category = '$offer_category' AND offers.status='enabled' AND users.status='enabled'";
        $statement = $pdo->query($req);

        while ($data = $statement->fetch()) {

            $offer_id = $data['id'];
            $titre = $data['title'];
            $description = $data['description'];
            $arrondissement = $data['arrondissement'];
            $name = $data['name'];
            $firstname = $data['firstname'];
            
            echo "Annonce $offer_id : $titre - desc: $description - from: $firstname $name - arr: $arrondissement : <a href='/message/write/$offer_id/$user_id'>Réserver cette offre</a><br>";
        }
    }
    ?>
</div>
<?php
    require_once 'footer.php';
?>
