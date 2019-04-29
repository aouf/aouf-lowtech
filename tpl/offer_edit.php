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
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $req = "UPDATE offers SET title='$title',description='$description',status='$status' WHERE id=$offer_id";
    $statement = $pdo->prepare($req);
    $statement->execute();
}

?>
<div class="container bg-blanc noir full-size">
    <div class="content">

        <h2>Edition offre</h2>

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

        ?>

        <form class="full-size flex center column" method='post' enctype='multipart/form-data'>
        <section>
            <input type='radio' name='status' value='enabled' <?php if ($offer_status == 'enabled') print "checked"; ?>>Offre active
            &nbsp;<input type='radio' name='status' value='disabled' <?php if ($offer_status == 'disabled') print "checked"; ?>>Offre inactive<br>
        </section>
        <label for="title">Titre <span class="saumon">*</span></label><input type='text' name='title' value='<?php print $offer_title; ?>'>
        <p>Catégorie de l'offre : <?php echo $offer_category; ?></p>
        <label for="address">Adresse</label><input type='text' name='address'>
        <label for="allDay">Toute la journée <input type="checkbox" name="allDay" value="yes"></label>
        <section class="flex column center">
            <span>Début de l'offre</span>
                <section class="flex">
                    <section class="flex column center"><label for="dateStart">Jour</label><input type='date' name="dateStart" value="<?php echo date('Y-m-d') ;?>"></section>
                    <section class="flex column center"><label for="timeStart">Heure</label><input type='time' name="timeStart" value=""></section>
                </section>
        </section>
        <section class="flex column center">
            <span>Fin de l'offre</span>
                <section class="flex">
                    <section class="flex column center"><label for="dateEnd">Jour</label><input type='date' name="dateEnd" value="<?php echo date('Y-m-d', time() + 86400); ?>"></section>
                    <section class="flex column center"><label for="timeEnd">Heure</label><input type='time' name="timeEnd" value=""></section>
                </section>
        </section>
        <textarea name='description'><?php echo $offer_description; ?></textarea>
        <button class='bg-vert noir' type="submit" name="button" value"Modifier">Modifier</button>
        </form>

    </div>
</div>

<?php
require_once 'footer.php';
