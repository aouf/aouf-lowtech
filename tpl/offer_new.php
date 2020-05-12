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
    $show_offer = $_POST['show-offer'] == "true" ? 1 : 0;
    if (!ctype_digit($arrondissement)) { print "<div class='erreur noir bg-saumon center'>Erreur, arrondissement invalide&nbsp;!</div>"; goto skip; }
    $address = ($_POST['address'] != "") ? strip_tags($_POST['address']) : null;
    //if (($address != null)&&(!ctype_print($address))) { print "<div class='erreur noir bg-saumon center'>Erreur, adresse invalide&nbsp;!</div>"; goto skip; }
    $date_start = $_POST['dateStart'].' '.$_POST['timeStart'];
    $date_end = $_POST['dateEnd'].' '.$_POST['timeEnd'];
    $description = $_POST['description1']."\n".$_POST['description2']."\n".$_POST['description3'];
    $picture = ($_FILES['picture']['tmp_name']) ? file_get_contents($_FILES['picture']['tmp_name']) : 'NULL';

    $verifreq = "SELECT count(id) FROM offers WHERE id = (SELECT MAX(id) FROM offers) AND user_id=$user_id AND category=".$pdo->quote($category)." AND title=".$pdo->quote($title)." AND description=".$pdo->quote($description )." AND arrondissement=$arrondissement;";
    $verifstatement = $pdo->query($verifreq);
    $verifdata = $verifstatement->fetch();
    if($verifdata["count(id)"] == 0)
    {
        // insertion de l'offre en base
        $req = "INSERT INTO offers(user_id,category,title,description,status,date_start,date_end,arrondissement,address,picture,offer_type,show_offer) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $statement = $pdo->prepare($req);
        if ($statement->execute([$user_id,$category,$title,$description,'enabled',$date_start,$date_end,$arrondissement,$address,$picture,'offer',$show_offer])) {

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

Nouvelle offre postée par l'utilisateur $user_id :

$title

$description

--
L'equipe Aouf
";
            mail($conf['mail']['admin'],'[aouf] Nouvelle offre',$body_mail,$headers_mail);

            // Temporaire : Notification SMS pour tous les deloges (TODO : filtrer selon accept_mailing / arrondissement)
            $req = "select phonenumber from users where category='deloge' and status='enabled' and ( notification='sms' or notification='email+sms' )";
            $statement = $pdo->query($req);
            while ($data = $statement->fetch()) {
                $phone_number = $data['phonenumber'];
                $body_sms = 'Nouvelle+offre+via+AOUF+:+https://beta.aouf.fr/accueil';
                $ch = curl_init("https://api.smsmode.com/http/1.6/sendSMS.do?accessToken=".$conf['sms']['smsmodeapikey']."&message=".$body_sms."&numero=$phone_number");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_exec($ch);
                curl_close($ch);
            }

            echo "<div class='erreur noir bg-saumon center'>Offre <strong>$title</strong> postée, merci&nbsp;!</div>";
        } else {
            echo "<div class='erreur noir bg-saumon center'>Erreur à la création : titre invalide ou autre erreur...<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";
        }

skip:
}
else {
    echo "<div class='erreur noir bg-saumon center'>Cette offre a déjà été postée.</div>";
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
$placeholdertitre = "Ce que je propose en 1 ligne…";
$description = "Décrivez ce que vous proposez";
$placeholder1 = "Je propose…";
$placeholder2 = "Je suis disponible pour l'échange le week-end…";
$placeholder3 = "Vous pouvez me joindre via la messagerie sécurisé de la plateforme Aouf… ou par email/téléphone…";
if (preg_match('#^/offer/new/restauration#', $uri)) {
    $category = 'restauration';
    $placeholdertitre = "Couscous végétarien, mercredi midi, pour 6 personnes";
    $description = "Détails sur l'offre de restauration";
    $placeholder1 = "Je propose un repas complet pour 3 personnes…";
    $placeholder2 = "Le repas peut être consommé sur place ou à emporter…";
    $placeholder3 = "Le repas est de type sans porc / végétarien / végétalien / hallal / casher / sans gluten…";
} elseif (preg_match('#^/offer/new/blanchisserie#', $uri)) {
    $category = 'blanchisserie';
    $placeholdertitre = "Lessive à mon domicile le lundi";
    $description = "Préciser le lieu, si la lessive est fournie, si le séchage a lieu sur place, la quantité possible et les plages horaires auxquelles vous êtes disponible";
} elseif (preg_match('#^/offer/new/mobilite#', $uri)) {
    $category = 'mobilite';
    $description = "Décrivez ce que vous proposez (temps disponible ? place dans votre véhicule ? etc.). Vous vous engagez à être assuré et en possession d'un permis de conduire valide.";
    $placeholder1 = "Je propose un trajet en (modèle du véhicule) (place et taille) (vos disponibilités)";
    $placeholder2 = "Je peux aider à charger/ décharger le véhicule ou j’ai besoin d’aide";
} elseif (preg_match('#^/offer/new/loisir#', $uri)) {
    $category = 'loisir';
    $description = "Décrivez l'activité de e-loisir que vous proposez (pour qui ? pour combien ? comment y accéder ? etc.)
";
} elseif (preg_match('#^/offer/new/don#', $uri)) {
    $category = 'don';
    $description = "Précisez ce que vous donnez et la taille s’il s’agit de vêtements";
} elseif (preg_match('#^/offer/new/course#', $uri)) {
    $category = 'course';
    $description = "Détails sur les courses que vous pouvez faire, si vous avez un véhicule, besoin de sacs, etc.";
} elseif (preg_match('#^/offer/new/pret#', $uri)) {
    $category = 'pret';
    $description = "Détails sur l'objet que vous pouvez prêter, combien de temps, comment le remettre ?";
//} elseif (preg_match('#^/offer/new/autre#', $uri)) {
} else {
    $category = 'autre';
}
?>
<div class="container bg-blanc noir full-size">
    <div class="content">

        <h2>Je propose une offre de <?php echo $category; ?></h2>
            <form class="full-size flex center column" method='post' enctype='multipart/form-data'>
            <label for="title">Titre <span class="saumon">*</span></label>
            <input type='text' name='title' placeholder='<?php echo $placeholdertitre; ?>' required>
            <label for="">Arrondissement (Marseille)<span class="saumon">*</span></label>
            <select name='arrondissement' required>
                <option value='0' selected='selected' disabled='disabled'>Je choisis l'arrondissement où se trouve mon offre</option>
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
            <label for="address">Adresse (facultatif)</label>
            <input type='text' name='address' placeholder="Je donne l'adresse où se trouve mon offre" value='<?php print $user_address; ?>'>
            <!--<label for="allDay">Toute la journée <input type="checkbox" name="allDay" value="yes"></label>-->
            <section class="flex column center">
                <span>Début de l'offre <span class="saumon">*</span></span>
                    <section class="flex">
                        <section class="flex column center"><label for="dateStart">Jour</label><input type='date' name="dateStart" min="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>"></section>
                        <section class="flex column center"><label for="timeStart">Heure</label><input type='time' name="timeStart" value="<?php echo date('H:i'); ?>"></section>
                    </section>
            </section>
            <section class="flex column center">
                <span>Fin de l'offre <span class="saumon">*</span></span>
                    <section class="flex">
                        <section class="flex column center"><label for="dateEnd">Jour</label><input type='date' name="dateEnd" min="<?php echo date('Y-m-d', time() + 7200); ?>" value="<?php echo date('Y-m-d', time() + 3456000); ?>"></section>
                        <section class="flex column center"><label for="timeEnd">Heure</label><input type='time' name="timeEnd" value="<?php echo date('H:i', time() + 3456000); ?>"></section>
                    </section>
            </section>
            <p><?php echo $description ?> <span class="saumon">*</span></p>
            <textarea name='description1' placeholder="<?php echo $placeholder1; ?>" required></textarea>
            <textarea name='description2' placeholder="<?php echo $placeholder2; ?>"></textarea>
            <textarea name='description3' placeholder="<?php echo $placeholder3; ?>"></textarea>
            <label for="">Photo illustrant l'offre (facultatif)</label><input type='file' name='picture'>
            <label for="show-offer">Rendre mon offre visible pour les bénévoles</label><input type='checkbox' name='show-offer' value="true">
            <input type='hidden' name='category' value='<?php echo $category; ?>'>
            <button class='bg-vert noir' type="submit" name="button" value="Publier">Publier</button>
            </form>

    </div>
</div>
<?php
require_once 'footer.php';
