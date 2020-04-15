<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')&&($_SESSION['user_category']!='coordinateur')) {
    die("permission denied");
}

$arrondissement = $_SESSION['user_arrondissement'];
$arrondissement == '1' ? $arrondissement_beautify = $arrondissement.'er' : $arrondissement_beautify = $arrondissement.'ème' ;
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$max_length = 60;

if ($_SESSION['user_category']=='admin') 
{
    echo '<a href="/admin/moderation">
        <div class="header2 bg-saumon flex center">
            <h2 class="blanc margin-left">Modération</h2>
            <img class="fleche-droite" src="../images/fleche-droite-blanche.png" alt="">
        </div>
    </a>';
}
if ($_SESSION['user_category']=='admin' || $_SESSION['user_category']=='benevole') 
{
    echo '<a href="/offer/mylist">
        <div class="header2 bg-saumon flex center">
            <h2 class="blanc margin-left">Mes offres</h2>
            <img class="fleche-droite" src="../images/fleche-droite-blanche.png" alt="">
        </div>
    </a>';
    echo '<a href="/offer/yourlist">
        <div class="header2 bg-blanc flex center">
            <h2 class="saumon margin-left">Besoins exprimés</h2>
            <img class="fleche-droite" src="../images/fleche-droite-saumon.png" alt="">
        </div>
    </a>';
}
else
{
    echo '<a href="/offer/mylist">
        <div class="header2 bg-saumon flex center">
            <h2 class="blanc margin-left">Mes besoins</h2>
            <img class="fleche-droite" src="../images/fleche-droite-blanche.png" alt="">
        </div>
    </a>';

    echo '<a href="/offer/yourlist">
        <div class="header2 bg-blanc flex center">
            <h2 class="saumon margin-left">Besoins exprimés</h2>
            <img class="fleche-droite" src="../images/fleche-droite-saumon.png" alt="">
        </div>
    </a>';

    echo '<div class="header2 bg-saumon flex center">
            <h2 class="blanc">Offres disponibles</h2>
        </div>';
}
?>

<?php
    // if ($_SESSION['user_category']=='admin') {
?>
    <!-- <a class="flex center" href='/admin/register'><div>Créer un accès</div></a> -->
    
<?php 
// }

if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='benevole')) {
?>
    
    <div class="header2 bg-saumon flex center">
            <h2 class="blanc">Déposer une offre</h2>
    </div>
    <div id="container-accueil" class="container bg-saumon">
        <div class="content">
            <section class="tableau-de-bord flex wrap">
                <a class="flex center column" href='/offer/new/restauration'>
                    <img class="icone" src="./images/restauration-ajout.png" alt="">
                    <h3>Restauration</h3>
                </a>
                <a class="flex center column" href='/offer/new/course'>
                    <img class="icone" src="./images/courses-ajout.png" alt="">
                    <h3>Course</h3>
                </a>
                <a class="flex center column" href='/offer/new/pret'>
                    <img class="icone" src="./images/prets-ajout.png" alt="">
                    <h3>Prêt</h3>
                </a>
                <a class="flex center column" href='/offer/new/loisir'>
                    <img class="icone" src="./images/eloisirs-ajout.png" alt="">
                    <h3>e-Loisir</h3>
                </a>
                <a class="flex center column" href='/offer/new/don'>
                    <img class="icone" src="./images/dons-ajout.png" alt="">
                    <h3>Dons</h3>
                </a>
                <a class="flex center column" href='/offer/new/autre'>
                    <img class="icone" src="./images/autre-ajout.png" alt="">
                    <h3>Autre</h3>
                </a>
            </section>

<?php } ?>
<?php
if (($_SESSION['user_category']=='admin')||($_SESSION['user_category']=='deloge')||($_SESSION['user_category']=='coordinateur')) 
{
    if ($_SESSION['user_category']=='admin') 
    {
        echo '<div class="header2 bg-blanc flex center">
                <h2 class="saumon">Offres disponibles</h2>
            </div>';
    }
?>
    <div id="container-accueil" class="container bg-saumon">
        <div class="content">
            <!--<section class="tableau-de-bord flex wrap">
                <a class="flex center column" href='/offer/list/restauration'>
                    <img class="icone" src="./images/restauration.png" alt="">
                    <h3>Restauration</h3>
                </a>
                <a class="flex center column" href='/offer/list/course'>
                    <img class="icone" src="./images/courses.png" alt="">
                    <h3>Course</h3>
                </a>
                <a class="flex center column" href='/offer/list/pret'>
                    <img class="icone" src="./images/prets.png" alt="">
                    <h3>Prêt</h3>
                </a>
                <a class="flex center column" href='/offer/list/loisir'>
                    <img class="icone" src="./images/eloisirs.png" alt="">
                    <h3>e-Loisir</h3>
                </a>
                <a class="flex center column" href='/offer/list/don'>
                    <img class="icone" src="./images/dons.png" alt="">
                    <h3>Dons</h3>
                </a>
                <a class="flex center column" href='/offer/list/autre'>
                    <img class="icone" src="./images/autre.png" alt="">
                    <h3>Autre</h3>
                </a>
            </section>-->
</div>
    <div class="bg-saumon list-offres">
        <?php

        if (($arrondissement != '')&&($arrondissement != '0')) {

            echo "<h3>Offres dans votre arrondissement ($arrondissement_beautify)</h3>";
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, offers.category, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.offer_type = 'offer' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement=$arrondissement AND offers.date_start < NOW() and offers.date_end > NOW() ORDER BY offers.id DESC";
            $statement = $pdo->query($req);
            
            while ($data = $statement->fetch()) {
                $offer_id = $data['id'];
                $offer_userid = $data['user_id'];
                $titre = ucfirst($data['title']);
                $description = $data['description'];
                if (strlen($description) > $max_length)
                {
                    $offset = ($max_length - 3) - strlen($description);
                    $description = substr($description, 0, strrpos($description, ' ', $offset)) . '...';
                }
                $category= $data['category'];
                $offer_arrondissement = $data['arrondissement'];
                $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;
                $name = $data['name'];
                $firstname = $data['firstname'];
                $debut = date('d/m/y', strtotime($data['date_start']));
                $fin = date('d/m/y', strtotime($data['date_end']));
                $intervalle = "$debut - $fin";
                if ($debut == $fin) $intervalle = $debut;
                if (($debut == $fin)&&($debut == date('d/m/y'))) $intervalle = "aujourd'hui";

                if ($data['picture'] != 'NULL') {
                    $picture = "data:image/jpg;base64,".base64_encode($data['picture']);
                } else {
                    if ($category == 'don') $picture = "https://beta.aouf.fr/images/dons.png";
                    elseif ($category == 'restauration') $picture = "https://beta.aouf.fr/images/restauration.png";
                    elseif ($category == 'blanchisserie') $picture = "https://beta.aouf.fr/images/blanchisserie.png";
                    elseif ($category == 'course') $picture = "https://beta.aouf.fr/images/courses.png";
                    elseif ($category == 'loisir') $picture = "https://beta.aouf.fr/images/eloisirs.png";
                    elseif ($category == 'mobilite') $picture = "https://beta.aouf.fr/images/mobilite.png";
                    elseif ($category == 'pret') $picture = "https://beta.aouf.fr/images/prets.png";
                    else $picture = "https://beta.aouf.fr/images/autre.png";
                }
                ?>
                
                <a class='offre flex bg-blanc' href='<?php echo "/offer/show/$offer_id/$offer_userid"; ?>'>
                        <div class='bloc-offre bloc-offre-text'>
                            <div id='parallelogram' class='bg-blanc parallelogram-text'>
                                <p class='noskew'>
                                    <span class='noir titre-offre'><?php echo $titre; ?></span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'><?php echo "$intervalle <image class='ico-mini' src='/images/localisation.png' />  ".$offer_arrondissement; ?></span><br><span class='description noir'><?php echo $description; ?></span>
                                </p>
                            </div>
                        </div>
                        <div class='bloc-offre bloc-offre-image'>
                            <div id='parallelogram' class='parallelogram-img'>
                                <div class='image noskew' style='background-image: url("<?php echo $picture; ?>")'></div>
                            </div>
                        </div>
                </a>
            <?php }
            echo "<hr><h3>Offres dans les autres arrondissements</h3>";
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, offers.category, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.offer_type = 'offer' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement!=$arrondissement AND offers.date_start < NOW() and offers.date_end > NOW() ORDER BY offers.id DESC";

            $statement = $pdo->query($req);
            
            while ($data = $statement->fetch()) {
                // die(var_dump($data));
                $offer_id = $data['id'];
                $offer_userid = $data['user_id'];
                $titre = ucfirst($data['title']);
                $description = $data['description'];
                if (strlen($description) > $max_length)
                {
                    $offset = ($max_length - 3) - strlen($description);
                    $description = substr($description, 0, strrpos($description, ' ', $offset)) . '...';
                }
                $category= $data['category'];
                $offer_arrondissement = $data['arrondissement'];
                $name = $data['name'];
                $firstname = $data['firstname'];
                $debut = date('d/m/y', strtotime( $data['date_start']));
                $fin = date('d/m/y', strtotime($data['date_end']));
                $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;
                $intervalle = "$debut - $fin";
                if ($debut == $fin) $intervalle = $debut;
                if (($debut == $fin)&&($debut == date('d/m/y'))) $intervalle = "aujourd'hui";
                if ($data['picture'] != 'NULL') {
                    $picture = "data:image/jpg;base64,".base64_encode($data['picture']);
                } else {
                    if ($category == 'don') $picture = "https://beta.aouf.fr/images/dons.png";
                    elseif ($category == 'restauration') $picture = "https://beta.aouf.fr/images/restauration.png";
                    elseif ($category == 'blanchisserie') $picture = "https://beta.aouf.fr/images/blanchisserie.png";
                    elseif ($category == 'course') $picture = "https://beta.aouf.fr/images/courses.png";
                    elseif ($category == 'loisir') $picture = "https://beta.aouf.fr/images/eloisirs.png";
                    elseif ($category == 'mobilite') $picture = "https://beta.aouf.fr/images/mobilite.png";
                    elseif ($category == 'pret') $picture = "https://beta.aouf.fr/images/prets.png";
                    else $picture = "https://beta.aouf.fr/images/autre.png";
                }
                ?>
                
                <a class='offre flex bg-blanc' href='<?php echo "/offer/show/$offer_id/$offer_userid"; ?>'>
                        <div class='bloc-offre bloc-offre-text'>
                            <div id='parallelogram' class='bg-blanc parallelogram-text'>
                                <p class='noskew'>
                                    <span class='noir titre-offre'><?php echo $titre; ?></span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'><?php echo "$intervalle <image class='ico-mini' src='/images/localisation.png' />  ".$offer_arrondissement; ?></span><br><span class='description noir'><?php echo $description; ?></span>
                                </p>
                            </div>
                        </div>
                        <div class='bloc-offre bloc-offre-image'>
                            <div id='parallelogram' class='parallelogram-img'>
                                <div class='image noskew' style='background-image: url("<?php echo $picture; ?>")'></div>
                            </div>
                        </div>
                </a>
            <?php }
            } else {
            
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, offers.category, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.offer_type = 'offer' AND offers.status='enabled' AND users.status='enabled' AND offers.date_start < NOW() and offers.date_end > NOW() ORDER BY offers.id DESC";
            $statement = $pdo->query($req);
            
            while ($data = $statement->fetch()) {
                
                $offer_id = $data['id'];
                $offer_userid = $data['user_id'];
                $titre = ucfirst($data['title']);
                $description = $data['description'];
                if (strlen($description) > $max_length)
                {
                    $offset = ($max_length - 3) - strlen($description);
                    $description = substr($description, 0, strrpos($description, ' ', $offset)) . '...';
                }
                $category= $data['category'];
                $offer_arrondissement = $data['arrondissement'];
                $name = $data['name'];
                $firstname = $data['firstname'];
                $debut = date('d/m/y', strtotime($data['date_start']));
                $fin = date('d/m/y', strtotime( $data['date_end']));
                $offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er' : $offer_arrondissement = $offer_arrondissement.'ème' ;
                $intervalle = "$debut - $fin";
                if ($debut == $fin) $intervalle = $debut;
                if (($debut == $fin)&&($debut == date('d/m/y'))) $intervalle = "aujourd'hui";

                //echo "<a class='offre' href='/offer/show/$offer_id/$offer_userid'>";
                if ($data['picture'] != 'NULL') {
                    $picture = "data:image/jpg;base64,".base64_encode($data['picture']);
                } else {
                    if ($category == 'don') $picture = "https://beta.aouf.fr/images/dons.png";
                    elseif ($category == 'restauration') $picture = "https://beta.aouf.fr/images/restauration.png";
                    elseif ($category == 'blanchisserie') $picture = "https://beta.aouf.fr/images/blanchisserie.png";
                    elseif ($category == 'course') $picture = "https://beta.aouf.fr/images/courses.png";
                    elseif ($category == 'loisir') $picture = "https://beta.aouf.fr/images/eloisirs.png";
                    elseif ($category == 'mobilite') $picture = "https://beta.aouf.fr/images/mobilite.png";
                    elseif ($category == 'pret') $picture = "https://beta.aouf.fr/images/prets.png";
                    else $picture = "https://beta.aouf.fr/images/autre.png";
                }
                ?>
                
                <a class='offre flex bg-blanc' href='<?php echo "/offer/show/$offer_id/$offer_userid"; ?>'>
                        <div class='bloc-offre bloc-offre-text'>
                            <div id='parallelogram' class='bg-blanc parallelogram-text'>
                                <p class='noskew'>
                                    <span class='noir titre-offre'><?php echo $titre; ?></span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'><?php echo "$intervalle <image class='ico-mini' src='/images/localisation.png' />  ".$offer_arrondissement; ?></span><br><span class='description noir'><?php echo $description; ?></span>
                                </p>
                            </div>
                        </div>
                        <div class='bloc-offre bloc-offre-image'>
                            <div id='parallelogram' class='parallelogram-img'>
                                <div class='image noskew' style='background-image: url("<?php echo $picture; ?>")'></div>
                            </div>
                        </div>
                </a>
            <?php }
        }
        ?>

<?php
}?>

    </div>
</div>
<?php require_once 'footer.php'; ?>
