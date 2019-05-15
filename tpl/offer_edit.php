<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')) {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/offer/edit/(\d+)$#', $uri, $matches);
$offer_id = (int)$matches[1];
if (!($offer_id>0)) {
    print "Erreur, offre inexistante";
    die();
}

if (isset($_POST['title'])) {
    $status = $_POST['status'];
    $title = $_POST['title'];
    $arrondissement = $_POST['arrondissement'];
    $address = $_POST['address'];
    $date_start = $_POST['dateStart'].' '.$_POST['timeStart'];
    $date_end = $_POST['dateEnd'].' '.$_POST['timeEnd'];
    $description = $_POST['description'];
    if ($_FILES['picture']['tmp_name']) {
        $picture = file_get_contents($_FILES['picture']['tmp_name']);
        $req = "UPDATE offers SET title='$title',description='$description',status='$status',arrondissement='$arrondissement',address='$address',date_start='$date_start',date_end='$date_end',picture=? WHERE id=$offer_id";
        $statement = $pdo->prepare($req);
        $statement->execute([$picture]);
    } else {
        $req = "UPDATE offers SET title='$title',description='$description',status='$status',arrondissement='$arrondissement',address='$address',date_start='$date_start',date_end='$date_end' WHERE id=$offer_id";
        $statement = $pdo->prepare($req);
        $statement->execute();
    }

    // on met a jour le lastactivity de l'utilisateur
    $lastactivity = date('Y-m-d H:i:s');
    $req = "UPDATE users set date_lastactivity = $lastactivity WHERE id = $user_id";
    $statement = $pdo->prepare($req);
    $statement->execute();

    // notification par email
    $headers_mail = "MIME-Version: 1.0\n";
    $headers_mail .= 'From: '.$conf['mail']['from']."\n";
    $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
    $body_mail = "Bonjour,

Modification offre postée par l'utilisateur $user_id :

$title

$description

--
L'equipe Aouf
";
    mail($conf['mail']['admin'],'[aouf] Modification offre',$body_mail,$headers_mail);

    echo "Offre <strong>$title</strong> modifiée&nbsp;!<br>";
}

?>
<div class="container bg-blanc noir full-size">
    <div class="content">

        <?php

        $req = "SELECT * FROM offers where id = $offer_id LIMIT 1";
        $statement = $pdo->query($req);
        $data = $statement->fetch();
        $offer_title = $data['title'];
        $offer_category = $data['category'];
        $offer_description = $data['description'];
        $offer_description = $data['description'];
        $offer_status = $data['status'];
        $offer_userid = $data['user_id'];
        $offer_arrondissement = $data['arrondissement'];
        $offer_address = $data['address'];
        $offer_picture = $data['picture'];
        list($offer_datestart,$offer_timestart) = preg_split("/ /", $data['date_start']);
        list($offer_dateend,$offer_timeend) = preg_split("/ /", $data['date_end']);
        ?>

        <h2>Édition de mon offre de <?php echo $offer_category; ?></h2>

        <form class="full-size flex center column" method='post' enctype='multipart/form-data'>
        <section>
            <input type='radio' name='status' value='enabled' <?php if ($offer_status == 'enabled') print "checked"; ?>> Offre active
            &nbsp;<input type='radio' name='status' value='disabled' <?php if ($offer_status == 'disabled') print "checked"; ?>> Offre inactive<br>
        </section>
        <label for="title">Titre <span class="saumon">*</span></label><input type='text' name='title' value='<?php print $offer_title; ?>' required>
        <label for="">Arrondissement (Marseille)<span class="saumon">*</span></label>
        <select name='arrondissement' required>
            <option value='1' <?php if ($offer_arrondissement == 1) print "selected='selected'"; ?>>Marseille 1er</option>
            <option value='2' <?php if ($offer_arrondissement == 2) print "selected='selected'"; ?>>Marseille 2eme</option>
            <option value='3' <?php if ($offer_arrondissement == 3) print "selected='selected'"; ?>>Marseille 3eme</option>
            <option value='4' <?php if ($offer_arrondissement == 4) print "selected='selected'"; ?>>Marseille 4eme</option>
            <option value='5' <?php if ($offer_arrondissement == 5) print "selected='selected'"; ?>>Marseille 5eme</option>
            <option value='6' <?php if ($offer_arrondissement == 6) print "selected='selected'"; ?>>Marseille 6eme</option>
            <option value='7' <?php if ($offer_arrondissement == 7) print "selected='selected'"; ?>>Marseille 7eme</option>
            <option value='8' <?php if ($offer_arrondissement == 8) print "selected='selected'"; ?>>Marseille 8eme</option>
            <option value='9' <?php if ($offer_arrondissement == 9) print "selected='selected'"; ?>>Marseille 9eme</option>
            <option value='10' <?php if ($offer_arrondissement == 10) print "selected='selected'"; ?>>Marseille 10eme</option>
            <option value='11' <?php if ($offer_arrondissement == 11) print "selected='selected'"; ?>>Marseille 11eme</option>
            <option value='12' <?php if ($offer_arrondissement == 12) print "selected='selected'"; ?>>Marseille 12eme</option>
            <option value='13' <?php if ($offer_arrondissement == 13) print "selected='selected'"; ?>>Marseille 13eme</option>
            <option value='14' <?php if ($offer_arrondissement == 14) print "selected='selected'"; ?>>Marseille 14eme</option>
            <option value='15' <?php if ($offer_arrondissement == 15) print "selected='selected'"; ?>>Marseille 15eme</option>
            <option value='16' <?php if ($offer_arrondissement == 16) print "selected='selected'"; ?>>Marseille 16eme</option>
        </select>
        <label for="address">Adresse (facultative)</label><input type='text' name='address' value='<?php print $offer_address; ?>' placeholder="Je donne l'adresse où se trouve mon offre">
        <!--<label for="allDay">Toute la journée <input type="checkbox" name="allDay" value="yes"></label>-->
        <section class="flex column center">
            <span>Début de l'offre</span>
                <section class="flex">
                    <section class="flex column center"><label for="dateStart">Jour</label><input type='date' name="dateStart" value="<?php echo $offer_datestart; ?>"></section>
                    <section class="flex column center"><label for="timeStart">Heure</label><input type='time' name="timeStart" value="<?php echo preg_replace('/(\d\d:\d\d):\d\d/','$1',$offer_timestart); ?>"></section>
                </section>
        </section>
        <section class="flex column center">
            <span>Fin de l'offre</span>
                <section class="flex">
                    <section class="flex column center"><label for="dateEnd">Jour</label><input type='date' name="dateEnd" value="<?php echo $offer_dateend; ?>"></section>
                    <section class="flex column center"><label for="timeEnd">Heure</label><input type='time' name="timeEnd" value="<?php echo preg_replace('/(\d\d:\d\d):\d\d/','$1',$offer_timeend); ?>"></section>
                </section>
        </section>
        <textarea name='description' required><?php echo $offer_description; ?></textarea>
        <?php if ($offer_picture == 'NULL') { ?><label for="photo">Photo illustrant l'offre</label><input type='file' name='picture'> <?php } ?>
        <button class='bg-vert noir' type="submit" name="button" value="Modifier">Modifier</button>
        </form>

    </div>
</div>

<?php
require_once 'footer.php';
