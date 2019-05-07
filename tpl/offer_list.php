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
    <h2 class="saumon"><?php echo ucfirst($offer_category); ?></h2>
    <div class="bg-saumon full-size list-offres">
        <?php
        if ($arrondissement != '') {

            echo "<h3>Offres dans votre arrondissement ($arrondissement)</h3>";
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.category = '$offer_category' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement=$arrondissement";
            $statement = $pdo->query($req);
            
            while ($data = $statement->fetch()) {
                $offer_id = $data['id'];
                $offer_userid = $data['user_id'];
                $titre = $data['title'];
                $description = $data['description'];
                $offer_arrondissement = $data['arrondissement'];
                $name = $data['name'];
                $firstname = $data['firstname'];
                $debut = date('d/m/y', strtotime($data['date_start']));
                $fin = date('d/m/y', strtotime($data['date_end']));
                $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;

                echo "<a class='offre' href='/message/write/$offer_id/$offer_userid'>";
                    echo "<div class='flex'>";
                        echo "<div class='oblique-gauche bg-blanc'>";
                            echo "<h3 class='noir'>".ucfirst($titre)."</h3>";
                            echo "<p class='date-lieu saumon'>$debut - $fin - $offer_arrondissement</p>";
                            echo "<p class='description noir'>$description</p>";
                        echo "</div>";
                        echo "<div class='oblique-droite bg-blanc'>";
                            if ($data['picture'] != 'NULL') {
                                $picture = base64_encode($data['picture']);
                                echo "<div class='img-offre' style='background-image: url(data:image/jpg;base64,$picture)''></div>";
                            }
                        echo "</div>";
                    echo "</div>";
                echo "</a>";
            }
            echo "<h3>Offres dans les autres arrondissements</h3>";
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.category = '$offer_category' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement!=$arrondissement";

            $statement = $pdo->query($req);
            
            while ($data = $statement->fetch()) {
                // die(var_dump($data));
                $offer_id = $data['id'];
                $offer_userid = $data['user_id'];
                $titre = $data['title'];
                $description = $data['description'];
                $offer_arrondissement = $data['arrondissement'];
                $name = $data['name'];
                $firstname = $data['firstname'];
                $debut = date('d/m/y', strtotime( $data['date_start']));
                $fin = date('d/m/y', strtotime($data['date_end']));
                $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;

                echo "<a class='offre' href='/message/write/$offer_id/$offer_userid'>";
                    echo "<div class='flex'>";
                        echo "<div class='oblique-gauche bg-blanc'>";
                            echo "<h3 class='noir'>".ucfirst($titre)."</h3>";
                            echo "<p class='date-lieu saumon'>$debut - $fin - $offer_arrondissement</p>";
                            echo "<p class='description noir'>$description</p>";
                        echo "</div>";
                        echo "<div class='oblique-droite bg-blanc'>";
                            if ($data['picture'] != 'NULL') {
                                $picture = base64_encode($data['picture']);
                                echo "<div class='img-offre' style='background-image: url(data:image/jpg;base64,$picture)''></div>";
                            }
                        echo "</div>";
                    echo "</div>";
                echo "</a>";
            }
        } else {
            
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.category = '$offer_category' AND offers.status='enabled' AND users.status='enabled'";
            $statement = $pdo->query($req);
            
            while ($data = $statement->fetch()) {
                
                $offer_id = $data['id'];
                $offer_userid = $data['user_id'];
                $titre = $data['title'];
                $description = $data['description'];
                $offer_arrondissement = $data['arrondissement'];
                $name = $data['name'];
                $firstname = $data['firstname'];
                $debut = date('d/m/y', strtotime($data['date_start']));
                $fin = date('d/m/y', strtotime( $data['date_end']));
                $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;
                
                echo "<a class='offre' href='/message/write/$offer_id/$offer_userid'>";
                    echo "<div class='flex'>";
                        echo "<div class='oblique-gauche bg-blanc'>";
                            echo "<h3 class='noir'>".ucfirst($titre)."</h3>";
                            echo "<p class='date-lieu saumon'>$debut - $fin - $offer_arrondissement</p>";
                            echo "<p class='description noir'>$description</p>";
                        echo "</div>";
                        echo "<div class='oblique-droite bg-blanc'>";
                            if ($data['picture'] != 'NULL') {
                                $picture = base64_encode($data['picture']);
                            echo "<div class='img-offre' style='background-image: url(data:image/jpg;base64,$picture)''></div>";
                            }
                        echo "</div>";
                    echo "</div>";
                echo "</a>";
            }
        }
        ?>
    </div>

</div>
<?php
    require_once 'footer.php';
?>
