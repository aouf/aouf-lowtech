<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

?>
<div class="container bg-blanc noir full-size">
    <h2>Liste de mes offres</h2>
    <div class="bg-saumon full-size list-offres">
    <?php
        $pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
        $req = "SELECT * FROM offers WHERE user_id = $user_id";
        $statement = $pdo->query($req);

        while ($data = $statement->fetch()) {

            $offer_id = $data['id'];
            $titre = $data['title'];
            $description = $data['description'];
            $offer_arrondissement = $data['arrondissement'];
            $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;
            $status = $data['status'];
            $status_text = "";
            if ($status == 'disabled') $status_text = "(désactivée)";
            $debut = date('d/m/y', strtotime($data['date_start']));
            $fin = date('d/m/y', strtotime($data['date_end']));

            echo "<a class='offre' href='/offer/edit/$offer_id'>";
                    echo "<div class='flex'>";
                        echo "<div class='oblique-gauche bg-blanc'>";
                            echo "<h3 class='noir'>".ucfirst($titre)." $status_text</h3>";
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

    ?>
    </div>
</div>
<?php
require_once 'footer.php';
