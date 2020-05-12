<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')&&($_SESSION['user_category']!='coordinateur')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];
$max_length = 60;
$user_arrondissement = $_SESSION['user_arrondissement'];
$user_arrondissement == '1' ? $arrondissement_beautify = $user_arrondissement.'er' : $arrondissement_beautify = $user_arrondissement.'ème' ;
if(isset($_GET['arrondissements'])) {
    $arrondissements = $_GET['arrondissements'];
}

?>
<div class="container bg-blanc noir">
    <h2 class="saumon"><?php echo ucfirst("Filtrer par arrondissement"); ?></h2>
    <form id="arrondissement-form" method="get">
        <dl class="dropdown"> 
            <dt>
                <a href="#" class="arrondissement-select">
                  <span class="hida">Arrondissements</span>    
                  <p class="multiSel"></p>  
                </a>
            </dt>
            <dd>
                <div class="mutliSelect">
                    <ul>
                        <?php
                        for ($i=1; $i <= 16; $i++) {
                            if (isset($arrondissements)) {
                                $checked = in_array($i, $arrondissements) ? "checked" : "";
                            }
                            if ($i == 1) {
                                echo '<li><input type="checkbox" value="'.$i.'" id="arrondissements'.$i.'" name="arrondissements[]" '.$checked.'/><label for="arrondissements'.$i.'">'.$i.'er arrondissement</label></li>';
                            } else {
                                echo '<li><input type="checkbox" value="'.$i.'" id="arrondissements'.$i.'" name="arrondissements[]" '.$checked.'/><label for="arrondissements'.$i.'">'.$i.'ème arrondissement</label></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </dd>
            <button class='bg-vert noir' type="submit" name="arrondissement-button" value="filtrer">Filtrer</button>
        </dl>
    </form>
    <h2 class="saumon"><?php echo ucfirst("Liste des besoins exprimés"); ?></h2>
    <div class="bg-saumon list-offres">
        <div class="content">
            <?php
            if(isset($arrondissements)) {
                echo "<h3>Besoins dans les arrondissements selectionnés (";
                foreach ($arrondissements as $arrondissement_number) {
                    if ($arrondissement_number == $arrondissements[0]) {
                        echo $arrondissement_number;
                    } else {
                        echo ", $arrondissement_number";
                    }
                }
                echo ")</h3>";
                    $pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
                    $req = "SELECT * FROM offers WHERE offer_type='besoin'";
                    foreach ($arrondissements as $arrondissement_number) {
                        if ($arrondissement_number == $arrondissements[0]) {
                            $req .= " AND arrondissement=$arrondissement_number";
                        } else {
                            $req .= " OR arrondissement=$arrondissement_number";
                        }
                    }
                    
                    $req .=" AND status='enabled' AND date_start < NOW() and date_end > NOW() AND user_id!=$user_id ORDER BY id DESC";
                    $statement = $pdo->query($req);
            } else {
                echo "<h3>Besoins dans votre arrondissement ($arrondissement_beautify)</h3>";
                $pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
                $req = "SELECT * FROM offers WHERE offer_type='besoin' AND arrondissement='$user_arrondissement' AND status='enabled' AND date_start < NOW() and date_end > NOW() AND user_id!=$user_id ORDER BY id DESC";
                $statement = $pdo->query($req);
            }
            while ($data = $statement->fetch()) {
                $offer_id = $data['id'];
                $offer_userid = $data['user_id'];
                $category = $data['category'];
                $collectif = $data['collectif'];
                $nb_children = $data['nb_children'] > 1 ? $data['nb_children'] ." enfants" : $data['nb_children'] . " enfant";

                if (($category != 'couches' && $_SESSION['user_category']!='admin') || ($category == 'couches' && $_SESSION['user_category']=='admin') || ($category != 'couches' && $_SESSION['user_category']=='admin') ){
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
                    echo "<a class='offre flex bg-blanc' href='/offer/show/$offer_id/$offer_userid'>";
                    echo" <div class='bloc-offre bloc-offre-text'>";
                    echo "<div id='parallelogram' class='bg-blanc parallelogram-text'>";
                    echo "<p class='noskew'>";
                    if ($category != 'couches') {
                        echo "<span class='noir titre-offre'>$titre</span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'>$intervalle <image class='ico-mini' src='/images/localisation.png' />  $offer_arrondissement</span><br><span class='description noir'>$description</span>";
                    } else {
                        echo "<span class='noir titre-offre'>$titre</span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'>$intervalle <image class='ico-mini' src='/images/localisation.png' />  $offer_arrondissement</span><br><span class='description noir'>Collectif : $collectif</span><br><span class='description noir'>Demande concernant $nb_children </span>";
                    }
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
            }
            if(!isset($arrondissements)) {
                echo "<hr><h3>Besoins dans les autres arrondissements</h3>";

                $pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
                $req = "SELECT * FROM offers WHERE offer_type='besoin' AND arrondissement!='$user_arrondissement' AND status='enabled' AND date_start < NOW() and date_end > NOW() AND user_id!=$user_id ORDER BY LENGTH(arrondissement), arrondissement ASC, id DESC";
                $statement = $pdo->query($req);

                while ($data = $statement->fetch()) {
                    $offer_id = $data['id'];
                    $offer_userid = $data['user_id'];
                    $category = $data['category'];
                    $collectif = $data['collectif'];
                    $nb_children = $data['nb_children'] > 1 ? $data['nb_children'] ." enfants" : $data['nb_children'] . " enfant";

                    if (($category != 'couches' && $_SESSION['user_category']!='admin') || ($category == 'couches' && $_SESSION['user_category']=='admin') || ($category != 'couches' && $_SESSION['user_category']=='admin') ){
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
                        echo "<a class='offre flex bg-blanc' href='/offer/show/$offer_id/$offer_userid'>";
                        echo" <div class='bloc-offre bloc-offre-text'>";
                        echo "<div id='parallelogram' class='bg-blanc parallelogram-text'>";
                        echo "<p class='noskew'>";
                        if ($category != 'couches') {
                            echo "<span class='noir titre-offre'>$titre</span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'>$intervalle <image class='ico-mini' src='/images/localisation.png' />  $offer_arrondissement</span><br><span class='description noir'>$description</span>";
                        } else {
                            echo "<span class='noir titre-offre'>$titre</span><br><image class='ico-mini' src='/images/horloge.png' /> <span class='date-lieu saumon'>$intervalle <image class='ico-mini' src='/images/localisation.png' />  $offer_arrondissement</span><br><span class='description noir'>Collectif : $collectif</span><br><span class='description noir'>Demande concernant $nb_children </span>";
                        }
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
                }
            }
            ?>
        </div>
    </div>
</div>
    <?php
require_once 'footer.php';
