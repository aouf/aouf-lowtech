<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='deloge')&&($_SESSION['user_category']!='coordinateur')) {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

if (isset($_POST['title'])) {
    $category = $_POST['category'];
    $title = preg_match('#^/offer/besoin/couche#', $uri) ? '[MCS] '.$_POST['title'] : $_POST['title'];
    $arrondissement = $_POST['arrondissement'];
    if (!ctype_digit($arrondissement)) { print "<div class='erreur noir bg-saumon center'>Erreur, arrondissement invalide&nbsp;!</div>"; goto skip; }
    $address = ($_POST['address'] != "") ? strip_tags($_POST['address']) : null;
    //if (($address != null)&&(!ctype_print($address))) { print "<div class='erreur noir bg-saumon center'>Erreur, adresse invalide&nbsp;!</div>"; goto skip; }
    $date_start = $_POST['dateStart'].' '.$_POST['timeStart'];
    $date_end = $_POST['dateEnd'].' '.$_POST['timeEnd'];
    if (!preg_match('#^/offer/besoin/couche#', $uri)) {
        $description = $_POST['description1']."\n".$_POST['description2']."\n".$_POST['description3'];
        $picture = ($_FILES['picture']['tmp_name']) ? file_get_contents($_FILES['picture']['tmp_name']) : 'NULL';
        $collectif = 'NULL';
        $referent_name = 'NULL';
        $referent_phonenumber = 'NULL';
        $panier = 0;
        $related_products = 'NULL';
        $nb_children = 0;
    } else {
        $description = 'NULL';
        $picture = 'NULL';
        $collectif = $_POST['collectif'] ? $_POST['collectif'] : 'NULL';
        $referent_name = $_POST['referent-name'] ? $_POST['referent-name'] : 'NULL';
        $referent_phonenumber = $_POST['referent-phonenumber'] ? $_POST['referent-phonenumber'] : 'NULL';
        $panier = $_POST['panier'] == "true" ? 1 : 0;
        $related_products = $_POST['related-products'] ? $_POST['related-products'] : 'NULL';
        $nb_children = $_POST['nb-children'];
    }
    
    $verifreq = "SELECT count(id) FROM offers WHERE id = (SELECT MAX(id) FROM offers) AND user_id=$user_id AND category=".$pdo->quote($category)." AND title=".$pdo->quote($title)." AND description=".$pdo->quote($description)." AND arrondissement=$arrondissement;";
    $verifstatement = $pdo->query($verifreq);
    $verifdata = $verifstatement->fetch();

    if($verifdata["count(id)"] == 0)
    {
        // insertion du besoin en base
        $req = "INSERT INTO offers(user_id,category,title,description,status,date_start,date_end,arrondissement,address,picture,offer_type,collectif,referent_name,referent_phonenumber,panier,related_products,nb_children) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $statement = $pdo->prepare($req);
        if ($statement->execute([$user_id,$category,$title,$description,'enabled',$date_start,$date_end,$arrondissement,$address,$picture,'besoin',$collectif,$referent_name,$referent_phonenumber,$panier,$related_products,$nb_children])) {

            // on met a jour le lastactivity de l'utilisateur
            $lastactivity = date('Y-m-d H:i:s');
            $req = "UPDATE users set date_lastactivity = '$lastactivity' WHERE id = $user_id";
            $statement = $pdo->prepare($req);
            $statement->execute();

            // notification par email
            $headers_mail = "MIME-Version: 1.0\n";
            $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
            $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
            $body_mail = "Bonjour,

    Nouveau besoin posté par l'utilisateur $user_id :

    $title

    $description

    --
    L'equipe Aouf
    ";

            if ($category!='couches') {
                mail($conf['mail']['admin'],'[aouf] Nouveau besoin',$body_mail,$headers_mail);
            }

            $req = "select email,phonenumber,notification from users where category='benevole' and status='enabled'";
            $statement = $pdo->query($req);
            while ($data = $statement->fetch()) {
                $notification = $data['notification']; 
                // Notification email pour tous les benevoles (TODO : filtrer selon accept_mailing / arrondissement)
                $email_addr = $data['email'];
                if ((($notification == 'email')||($notification == 'email+sms'))&&($email_addr != '')) {
                    $headers_mail = "MIME-Version: 1.0\n";
                    $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
                    $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
                    $body_mail = "Bonjour,

Un nouveau besoin a été posté sur AOUF !

Attention, merci de ne PAS répondre par email mais
de répondre via https://beta.aouf.fr/offer/yourlist

$title

$description

Rappel : merci de répondre via
    https://beta.aouf.fr/offer/yourlist

-- 
L'equipe Aouf
";
                    if ($category!='couches') {
                        mail($email_addr,'Nouveau besoin via Aouf',$body_mail,$headers_mail);
                    }
                }

                // Notification SMS pour tous les benevoles (TODO : filtrer selon arrondissement)
                if ($category!='couches') {
                    $phone_number = $data['phonenumber'];
                    if ((($notification == 'sms')||($notification == 'email+sms'))&&($phone_number != '')) {
                        $body_sms = 'Nouveau+besoin+via+AOUF+:+https://beta.aouf.fr/offer/yourlist';
                        $ch = curl_init("https://api.smsmode.com/http/1.6/sendSMS.do?accessToken=".$conf['sms']['smsmodeapikey']."&message=".$body_sms."&numero=$phone_number");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }

            if (preg_match('#^/offer/besoin/couche#', $uri)) {
                $offerquery = "SELECT MAX(id) FROM offers;";
                $offerstatement = $pdo->query($offerquery);
                $offerdata = $offerstatement->fetch();

                for ($i=1; $i <= intval($_POST['nb-children']); $i++) {
                    $offer_id = intval($offerdata[0]);
                    $child_name = $_POST['child-name-'.$i];
                    $child_age = $_POST['child-age-'.$i];
                    $child_weight = $_POST['child-poids-'.$i];
                    $layer_size = $_POST['layer-size-'.$i];
                    $milk = $_POST['milk-'.$i] == "true" ? 1 : 0;
                    $milk_age = $_POST['milk-age-'.$i];
                    $milk_brand = $_POST['milk-brand-'.$i];
                    $childreq = "INSERT INTO children(offer_id,child_name,child_age,child_weight,layer_size,milk,milk_age,milk_brand) VALUES (?,?,?,?,?,?,?,?)";
                    $childstatement = $pdo->prepare($childreq);
                    if (!$childstatement) {
                        echo "\nPDO::errorInfo():\n";
                        print_r($pdo->errorInfo());
                    }
                    if ($childstatement->execute([$offer_id,$child_name,$child_age,$child_weight,$layer_size,$milk,$milk_age,$milk_brand])) {
                        
                    } else {
                        echo "\nPDO::errorInfo():\n";
                        print_r($childstatement->errorInfo());
                        echo "<div class='erreur noir bg-saumon center'>Erreur pour le " . $i == 1 ? "1er" : $i. "ème". " enfant</div>";
                    }
                }
            }
            echo "<div class='erreur noir bg-saumon center'>Besoin <strong>$title</strong> posté, merci&nbsp;!</div>";
        } else {
            echo "<div class='erreur noir bg-saumon center'>Erreur à la création : titre invalide ou autre erreur...<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";
        }

    skip:
    }
    else {
        echo "<div class='erreur noir bg-saumon center'>Cette demande a déjà été postée.</div>";
    }
}


$req = "SELECT * FROM users where id = $user_id LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();
//$user_login = $data['login'];
//$user_name = $data['name'];
//$user_firstname = $data['firstname'];
//$user_email = $data['email'];
//$user_phonenumber = $data['phonenumber'];
$user_arrondissement = $data['arrondissement'];
$user_address = $data['address'];
//$user_notification = $data['notification'];
//$user_gender = $data['gender'];
//$user_accept_mailing = $data['accept_mailing'];

$uri = $_SERVER['REQUEST_URI'];
$placeholdertitre = "Ce dont j'ai besoin en 1 ligne…";
$description = "Décrivez ce dont vous avez besoin";
$placeholder1 = "J'ai besoin…";
$placeholder2 = "";
$placeholder3 = "";
if (preg_match('#^/offer/besoin/restauration#', $uri)) {
    $category = 'restauration';
    $placeholdertitre = "Repas végétarien mercredi midi pour 6 personnes";
    $description = "Détails sur le besoin de restauration";
    $placeholder1 = "J'ai besoin d'un repas complet pour 6 personnes…";
    $placeholder2 = "Le repas sera consommé sur place ou à emporter…";
    $placeholder3 = "Le repas est de type sans porc / végétarien / végétalien / hallal / casher / sans gluten…";
} elseif (preg_match('#^/offer/besoin/couche#', $uri)) {
    $category = 'couches';
    $placeholdertitre = "Couches pour 2 bébés";
    $description = "Préciser le lieu, si besoin de lessive, de séchage, la quantité nécessaire et les plages horaires auxquelles vous êtes disponible";
} elseif (preg_match('#^/offer/besoin/blanchisserie#', $uri)) {
    $category = 'blanchisserie';
    $placeholdertitre = "Lessive de draps blancs le lundi";
    $description = "Préciser le lieu, si besoin de lessive, de séchage, la quantité nécessaire et les plages horaires auxquelles vous êtes disponible";
} elseif (preg_match('#^/offer/besoin/mobilite#', $uri)) {
    $category = 'mobilite';
    $description = "Décrivez ce sont vous avez besoin (temps disponible ? place dans votre véhicule ? etc.).";
    $placeholder1 = "J'ai besoin d'un trajet en (type du véhicule) (place et taille) (vos disponibilités)";
    $placeholder2 = "Je peux aider à charger/ décharger le véhicule ou j’ai besoin d’aide";
} elseif (preg_match('#^/offer/besoin/loisir#', $uri)) {
    $category = 'loisir';
    $description = "Décrivez ce que vous avez besoin (activité ? pour qui ? où ? nombre de personnes ? etc.)
";
} elseif (preg_match('#^/offer/besoin/don#', $uri)) {
    $category = 'don';
    $description = "Précisez le type de don que vous souhaitez";
} elseif (preg_match('#^/offer/besoin/autre#', $uri)) {
    $category = 'autre';
}
?>
<div class="container bg-blanc noir full-size">
    <div class="content">

        <h2 style="margin:20px;">J'ai besoin de <?php echo $category; ?></h2>
            <form id="add-besoin" name="add-besoin" class="full-size flex center column" method='post' enctype='multipart/form-data' onsubmit="addHiddenInput()">
                <label for="title">Titre <span class="saumon">*</span></label>
                <input type='text' name='title' placeholder="<?php echo $placeholdertitre; ?>" required>
                <label for="arrondissement">Arrondissement (Marseille)<span class="saumon">*</span></label>
                <select name='arrondissement' required>
                    <option value='0' selected='selected' disabled='disabled'>Je choisis l'arrondissement où se trouve mon besoin</option>
                    <option value='1' <?php if ($user_arrondissement == 1) print "selected='selected'"; ?>>Marseille 1er</option>
                    <option value='2' <?php if ($user_arrondissement == 2) print "selected='selected'"; ?>>Marseille 2eme</option>
                    <option value='3' <?php if ($user_arrondissement == 3) print "selected='selected'"; ?>>Marseille 3eme</option>
                    <option value='4' <?php if ($user_arrondissement == 4) print "selected='selected'"; ?>>Marseille 4eme</option>
                    <option value='5' <?php if ($user_arrondissement == 5) print "selected='selected'"; ?>>Marseille 5eme</option>
                    <option value='6' <?php if ($user_arrondissement == 6) print "selected='selected'"; ?>>Marseille 6eme</option>
                    <option value='7' <?php if ($user_arrondissement == 7) print "selected='selected'"; ?>>Marseille 7eme</option>
                    <option value='8' <?php if ($user_arrondissement == 8) print "selected='selected'"; ?>>Marseille 8eme</option>
                    <option value='9' <?php if ($user_arrondissement == 9) print "selected='selected'"; ?>>Marseille 9eme</option>
                    <option value='10' <?php if ($user_arrondissement == 10) print "selected='selected'"; ?>>Marseille 10eme</option>
                    <option value='11' <?php if ($user_arrondissement == 11) print "selected='selected'"; ?>>Marseille 11eme</option>
                    <option value='12' <?php if ($user_arrondissement == 12) print "selected='selected'"; ?>>Marseille 12eme</option>
                    <option value='13' <?php if ($user_arrondissement == 13) print "selected='selected'"; ?>>Marseille 13eme</option>
                    <option value='14' <?php if ($user_arrondissement == 14) print "selected='selected'"; ?>>Marseille 14eme</option>
                    <option value='15' <?php if ($user_arrondissement == 15) print "selected='selected'"; ?>>Marseille 15eme</option>
                    <option value='16' <?php if ($user_arrondissement == 16) print "selected='selected'"; ?>>Marseille 16eme</option>
                </select>
                <label for="address">Adresse de livraison (facultatif)</label>
                <input type='text' name='address' placeholder="Je donne l'adresse où se trouve mon besoin" value='<?php print $user_address; ?>'>
                <!--<label for="allDay">Toute la journée <input type="checkbox" name="allDay" value="yes"></label>-->
                <section class="flex column center">
                    <span>Début du besoin <span class="saumon">*</span></span>
                        <section class="flex">
                            <section class="flex column center"><label for="dateStart">Jour</label><input type='date' name="dateStart" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>"></section>
                            <section class="flex column center"><label for="timeStart">Heure</label><input type='time' name="timeStart" value="<?php echo date('H:i'); ?>"></section>
                        </section>
                </section>
                <section class="flex column center">
                    <span>Fin du besoin <span class="saumon">*</span></span>
                        <section class="flex">
                            <section class="flex column center"><label for="dateEnd">Jour</label><input type='date' name="dateEnd" min="<?php echo date('Y-m-d', time() + 7200); ?>" value="<?php echo date('Y-m-d', time() + 3456000); ?>"></section>
                            <section class="flex column center"><label for="timeEnd">Heure</label><input type='time' name="timeEnd" value="<?php echo date('H:i', time() + 3456000); ?>"></section>
                        </section>
                </section>
                <?php if (!preg_match('#^/offer/besoin/couche#', $uri)) { ?>
                    <p><?php echo $description ?> <span class="saumon">*</span></p>
                    <textarea name='description1' placeholder="<?php echo $placeholder1; ?>" required></textarea>
                    <textarea name='description2' placeholder="<?php echo $placeholder2; ?>"></textarea>
                    <textarea name='description3' placeholder="<?php echo $placeholder3; ?>"></textarea>
                    <label for="">Photo illustrant le besoin (facultatif)</label><input type='file' name='picture'>
                <?php } ?>
                <?php if (preg_match('#^/offer/besoin/couche#', $uri)) { ?>
                    <section class="full-size flex center column">
                        <label for="collectif">Nom du collectif</label>
                        <input type="text" name="collectif" placeholder="Nom du collectif" required>
                        <section class="full-size flex center column">
                            <label for="referent-name">Prénom du référent</label>
                            <input type="text" name="referent-name" placeholder="Prénom" required>
                            <label for="referent-phonenumber">Numéro de téléphone du référent</label>
                            <input type="tel" name="referent-phonenumber" pattern="[0-9]{10}" required placeholder="0612345678">
                        </section>
                        <span style="margin:20px;">Bénéficiez-vous de paniers de légumes de la Métropole ?</span>
                        <div class="panier">
                            <label>Oui <input id="panier-oui" type="radio" name="panier" value="true" required></label>
                            <label>Non <input id="panier-non" type="radio" name="panier" value="false"></label>
                        </div>
                        <span style="margin:20px;">Informations à remplir pour chaque enfant en situation d'urgence</span>
                        <section class="full-size flex center column" style="padding:20px;">
                            <label for="child-name-1">Prénom du 1 er enfant</label>
                            <input type="text" name="child-name-1" class="child-name child-name-1" placeholder="Prénom" required>
                            <label for="child-age-1">Âge</label>
                            <input type="text" name="child-age-1" class="child-age child-age-1" placeholder="6 mois" required>
                            <label for="child-poids-1">Poids</label>
                            <input type="text" name="child-poids-1" class="child-poids child-poids-1" placeholder="7 kgs" required>
                            <label for="layer-size-1">Taille de couche</label>
                            <input type="text" name="layer-size-1" class="layer-size layer-size-1" placeholder="Taille 3" required>
                            <span style="margin:20px;">Besoin de lait ?</span>
                            <div class="milk milk-1">
                                <label>Oui <input type="radio" name="milk-1" class="milk milk-true-1" value="true" required></label>
                                <label>Non <input type="radio" name="milk-1" class="milk milk-false-1" value="false"></label>
                            </div>
                            <label for="marque" style="margin-top:20px;">Si oui, veuillez préciser l'âge</label>
                            <select name="milk-age-1" class="milk-age milk-age-1">
                                <option value="aucun">Pas de lait</option>
                                <option value="1">1 er âge</option>
                                <option value="2">2 ème âge</option>
                                <option value="croissance">Lait de croissance</option>
                                <option value="standard">Lait de vache standard</option>
                            </select>
                            <label for="milk-brand-1" style="margin-top:20px;">Marque</label>
                            <select name="milk-brand-1" class="milk-brand milk-brand-1">
                                <option value="aucune">Pas de marque précise (conseillé)</option>
                                <option value="bledilait">Bledilait</option>
                                <option value="gallia">Gallia</option>
                                <option value="guigoz">Guigoz</option>
                                <option value="physiolac">Physiolac</option>
                            </select>
                        </section>
                        <button id="add-child" type="button" name="button" value="add-child">Ajouter un enfant</button>
                        <textarea name='related-products' placeholder="Je souhaite des produits annexes (petits pots pour bébé, lingettes, produits d'hygiène, savons, shampooing)…"></textarea>
                    </section>
                <?php } ?>
                <input type='hidden' name='category' value='<?php echo $category; ?>'>
                <button class='bg-vert noir' type="submit" name="button" value="Publier">Publier</button>
            </form>

    </div>
</div>
<script type="text/javascript">
var addChild = document.getElementById('add-child');
var i = 1;
addChild.onclick = function() {
    i++;
    var parentDiv = addChild.parentNode;
    var newElement = document.createElement("section");
    newElement.setAttribute("id", "child-"+i);
    newElement.classList.add("child-"+i);
    newElement.classList.add("full-size");
    newElement.classList.add("flex");
    newElement.classList.add("center");
    newElement.classList.add("column");
    // newElement.classList.add("bg-saumon");
    newElement.style.padding = '20px';
    newElement.innerHTML = "<label for='child-name-"+i+"'>Prénom du "+i+" ème enfant</label><input type='text' name='child-name-"+i+"' class='child-name child-name-"+i+"' placeholder='Prénom' required><label for='child-age-"+i+"'>Âge</label><input type='text' name='child-age-"+i+"' class='child-age child-age-"+i+"' placeholder='6 mois' required><label for='child-poids-"+i+"'>Poids</label><input type='text' name='child-poids-"+i+"' class='child-poids child-poids-"+i+"' placeholder='7 kgs' required><label for='child-taille-"+i+"'>Taille de couche</label><input type='text' name='layer-size-"+i+"' class='layer-size layer-size-"+i+"' placeholder='Taille 3' required><span style='margin:20px;'>Besoin de lait ?</span><div class='milk milk-"+i+"'><label>Oui <input type='radio' name='milk-"+i+"' class='milk milk-true-"+i+"' value='true' required></label><label>Non <input type='radio' name='milk-"+i+"' class='milk milk-false-"+i+"' value='false'></label></div><label for='marque' style='margin-top:20px;'>Si oui, veuillez préciser l'âge</label><select name='milk-age-"+i+"' class='milk-age milk-age-"+i+"'><option value='aucun'>Pas de lait</option><option value='1'>1 er âge</option><option value='2'>2 ème âge</option><option value='croissance'>Lait de croissance</option><option value='standard'>Lait de vache standard</option></select><label for='milk-brand-"+i+"' style='margin-top:20px;'>Marque</label><select name='milk-brand-"+i+"' class='milk-brand milk-brand-"+i+"'><option value='aucune'>Pas de marque précise (conseillé)</option><option value='bledilait'>Bledilait</option><option value='gallia'>Gallia</option><option value='guigoz'>Guigoz</option><option value='physiolac'>Physiolac</option></select><button name='remove-child-"+i+"' id='remove-child-"+i+"' class='bg-saumon remove-child remove-child-"+i+"' type='button' value='remove-child' onclick='deleteThisChild(this)'>Retirer cet enfant</button>";
    parentDiv.insertBefore(newElement, addChild);
};

function deleteThisChild(el) {
    var idToArray = el.id.split("-");
    var id = idToArray[2];
    var sectionToDelete = document.getElementById("child-"+id);
    sectionToDelete.parentNode.removeChild(sectionToDelete);
    i--;
}

function addHiddenInput() {
    var besoin_form = document.forms['add-besoin'];
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'nb-children';
    input.value = i;
    besoin_form.appendChild(input);
}

</script>
<?php
require_once 'footer.php';
