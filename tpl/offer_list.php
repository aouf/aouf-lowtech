<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='deloge')&&($_SESSION['user_category']!='coordinateur')&&($_SESSION['user_category']!='benevole')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/offer/list/(\w+)$#', $uri, $matches);
$offer_category = trim($matches[1]);

$arrondissement = $_SESSION['user_arrondissement'];
$arrondissement == '1' ? $arrondissement_beautify = $arrondissement.'er' : $arrondissement_beautify = $arrondissement.'ème' ;
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);
$max_length = 60;
?>
<div class="container bg-blanc noir">
    <h2 class="saumon"><?php echo ucfirst($offer_category); ?></h2>
    <div class="bg-saumon list-offres">
        <?php

        if ($offer_category=='autre') {
            $offer_category_cond = " offers.category != 'restauration' AND offers.category != 'course' AND offers.category != 'pret' AND offers.category != 'loisir' AND offers.category != 'don' ";
        } else {
            $offer_category_cond = " offers.category = '$offer_category' ";
        }

        if (($arrondissement != '')&&($arrondissement != '0')) {

            echo "<h3>Offres dans votre arrondissement ($arrondissement_beautify)</h3>";
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, offers.category, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.user_id != $user_id AND offers.offer_type = 'offer' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement=$arrondissement AND offers.date_start < NOW() and offers.date_end > NOW()";
            if($_SESSION['user_category']=='benevole'){
                $req .=" AND show_offer > 0";
            }
            $req .= " ORDER BY offers.id DESC";
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
                    $picture = base64_encode($data['picture']);
                } else {
                    $picture = "";
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
                            <div class='image noskew' style='background-image: url("data:image/jpg;base64,<?php echo $picture; ?>")'></div>
                        </div>
                    </div>
                </a>
            <?php }
            echo "<hr><h3>Offres dans les autres arrondissements</h3>";
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, offers.category, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.user_id != $user_id AND offers.offer_type = 'offer' AND offers.status='enabled' AND users.status='enabled' AND offers.arrondissement!=$arrondissement AND offers.date_start < NOW() and offers.date_end > NOW()";
            if($_SESSION['user_category']=='benevole'){
                $req .=" AND show_offer > 0";
            }
            $req .= " ORDER BY LENGTH(offers.arrondissement), offers.arrondissement ASC, offers.id DESC";
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
                    $picture = base64_encode($data['picture']);
                } else {
                    $picture = "";
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
                                <div class='image noskew' style='background-image: url("data:image/jpg;base64,<?php echo $picture; ?>")'></div>
                            </div>
                        </div>
                </a>
            <?php }
            } else {
            
            $req = "SELECT offers.id, offers.user_id, offers.title, offers.description, offers.arrondissement, offers.picture, offers.date_start,  offers.date_end, users.name, users.firstname FROM offers,users WHERE offers.user_id = users.id AND offers.user_id != $user_id AND offers.offer_type = 'offer' AND $offer_category_cond AND offers.status='enabled' AND users.status='enabled' AND offers.date_start < NOW() and offers.date_end > NOW() ORDER BY offers.id DESC";
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
                    $picture = base64_encode($data['picture']);
                } else {
                    $picture = "";
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
                                <div class='image noskew' style='background-image: url("data:image/jpg;base64,<?php echo $picture; ?>")'></div>
                            </div>
                        </div>
                </a>
            <?php }
        }
        ?>
    </div>

</div>
<?php
    require_once 'footer.php';
?>
