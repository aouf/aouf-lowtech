<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')&&($_SESSION['user_category']!='coordinateur')) {
    die("permission denied");
}

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')) {
   $what = "Liste de mes besoins";
} else {
   $what = "Liste de mes offres".($_SESSION['user_category']=='admin' ? '/besoins' : '');
}

$user_id = $_SESSION['user_id'];
$max_length = 60;


if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='deloge' || $_SESSION['user_category']=='coordinateur') {
        echo '<div class="header2 bg-blanc flex center">
               <h2 class="saumon">Exprimer un nouveau besoin</h2>
           </div>';
?>
    <div id="container-accueil" class="container bg-saumon">
        <div class="content">
            <section class="tableau-de-bord flex wrap">
                <?php
                if ($_SESSION['user_category']=='deloge') {
                    ?>
                    <?php
                } elseif ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='coordinateur') {
                    ?>
                    <?php
                }
                ?>
                <a class="flex center column" href='/offer/besoin/logement'>
                    <img class="icone" src="../images/logement-ajout.png" alt="">
                    <h3>Logement</h3>
                </a>
                <a class="flex center column" href='/offer/besoin/restauration'>
                    <img class="icone" src="../images/restauration-ajout.png" alt="">
                    <h3>Repas</h3>
                </a>
                <a class="flex center column" href='/offer/besoin/blanchisserie'>
                    <img class="icone" src="../images/blanchisserie-ajout.png" alt="">
                    <h3>Lessive</h3>
                </a>
                <a class="flex center column" href='/offer/besoin/mobilite'>
                    <img class="icone" src="../images/mobilite-ajout.png" alt="">
                    <h3>Transport</h3>
                </a>
                <a class="flex center column" href='/offer/besoin/couche'>
                        <img class="icone" src="../images/couches-ajout.png" alt="">
                        <h3>Couches/lait</h3>
                </a>
                <a class="flex center column" href='/offer/besoin/loisirs'>
                    <img class="icone" src="../images/loisirs-ajout.png" alt="">
                    <h3>Loisirs</h3>
                </a>
                <a class="flex center column" href='/offer/besoin/don'>
                    <img class="icone" src="../images/dons-ajout.png" alt="">
                    <h3>Dons</h3>
                </a>
                <a class="flex center column" href='/offer/besoin/autre'>
                    <img class="icone" src="../images/autre-ajout.png" alt="">
                    <h3>Autre</h3>
                </a>
            </section>
        </div>
    </div>
<!--    <a class="feedback-link blanc" href="/whatineed">Pas trouvé d'annonce pour votre besoin&nbsp;? Dites le nous&nbsp;!</a> -->

<?php
}?>
<div class="container bg-blanc noir">
    <h2 class="saumon"><?php echo ucfirst($what); ?></h2>
    <div class="bg-saumon list-offres">
        <div class="content">
        <?php
            $pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
            $req = "SELECT * FROM offers WHERE user_id = $user_id";
            $statement = $pdo->query($req);

            while ($data = $statement->fetch()) {

                $offer_id = $data['id'];
                $titre = ucfirst($data['title']);

                $description = $data['description'];
                if (strlen($description) > $max_length)
                {
                    $offset = ($max_length - 3) - strlen($description);
                    $description = substr($description, 0, strrpos($description, ' ', $offset)) . '...';
                }

                $offer_arrondissement = $data['arrondissement'];
                $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;
                $status = $data['status'];
                $status_text = "";
                if ($status == 'disabled') $status_text = "(désactivée)";
                $debut = date('d/m/y', strtotime($data['date_start']));
                $fin = date('d/m/y', strtotime($data['date_end']));
                $intervalle = "$debut - $fin";
                if ($debut == $fin) $intervalle = $debut;
                if (($debut == $fin)&&($debut == date('d/m/y'))) $intervalle = "aujourd'hui";
                
                if ($data['picture'] != 'NULL') {
                    $picture = base64_encode($data['picture']);
                } else {
                    $picture = "";
                }

                echo "<a class='offre flex bg-blanc' href='/offer/edit/$offer_id'>";
                    echo" <div class='bloc-offre bloc-offre-text'>";
                        echo "<div id='parallelogram' class='bg-blanc parallelogram-text'>";
                            echo "<p class='noskew'>";
                                echo "<span class='noir titre-offre'>$titre</span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'>$intervalle <image class='ico-mini' src='/images/localisation.png' />  $offer_arrondissement</span><br><span class='description noir'>$description</span>";
                            echo "</p>";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='bloc-offre bloc-offre-image'>";
                        echo "<div id='parallelogram' class='parallelogram-img'>";
                            echo "<div class='image noskew' style='background-image: url(data:image/jpg;base64,$picture)'></div>";
                        echo "</div>";
                    echo "</div>";
                echo"</a>";

            }

        ?>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';
