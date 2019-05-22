<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

if (isset($_POST['login'])) {
    $login = $_POST['login'];
    if (!ctype_alnum($login)) { print "<div class='erreur noir bg-saumon center'>Erreur, login invalide&nbsp;!</div>"; goto skip; }
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $email = ($_POST['email'] != "") ? $_POST['email'] : NULL;
    $phone = $_POST['phone'];
    $arrondissement = $_POST['arrondissement'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    if ((isset($_POST['notif_email']))&&(isset($_POST['notif_sms']))) $notification = "email+sms";
    if ((isset($_POST['notif_email']))&&(!isset($_POST['notif_sms']))) $notification = "email";
    if ((isset($_POST['notif_sms']))&&(!isset($_POST['notif_email']))) $notification = "sms";
    if ((!isset($_POST['notif_email']))&&(!isset($_POST['notif_sms']))) $notification = "no";
    $acceptinfos = isset($_POST['acceptinfos']) ? 'yes' : 'no';

    if ($_POST['password'] != '') {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $req = "UPDATE users SET login=?,name=?,firstname=?,arrondissement=?,address=?,email=?,phonenumber=?,password=?,gender=?,accept_mailing=?,notification=? WHERE id=?";
        $statement = $pdo->prepare($req);
        $statement->execute([$login,$name,$firstname,$arrondissement,$address,$email,$phone,$password,$gender,$acceptinfos,$notification,$user_id]);
    } else {
        $req = "UPDATE users SET login=?,name=?,firstname=?,arrondissement=?,address=?,email=?,phonenumber=?,gender=?,accept_mailing=?,notification=? WHERE id=?";
        $statement = $pdo->prepare($req);
        $statement->execute([$login,$name,$firstname,$arrondissement,$address,$email,$phone,$gender,$acceptinfos,$notification,$user_id]);
    }

    $_SESSION['user_arrondissement'] = $arrondissement;

    // on met a jour le lastactivity de l'utilisateur
    $lastactivity = date('Y-m-d H:i:s');
    $req = "UPDATE users set date_lastactivity = $lastactivity WHERE id = $user_id";
    $statement = $pdo->prepare($req);
    $statement->execute();

    // notification par email
    $headers_mail = "MIME-Version: 1.0\n";
    $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
    $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
    $body_mail = "Bonjour,

Modification offre postée par l'utilisateur $user_id :

$name
$firstname

--
L'equipe Aouf
";
    mail($conf['mail']['admin'],'[aouf] Modification profil',$body_mail,$headers_mail);

    echo "<div class='erreur noir bg-saumon center'>Compte modifié&nbsp;!</div>";

skip:
}

$req = "SELECT * FROM users where id = $user_id LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();
$user_login = $data['login'];
$user_name = $data['name'];
$user_firstname = $data['firstname'];
$user_email = $data['email'];
$user_phonenumber = $data['phonenumber'];
$user_arrondissement = $data['arrondissement'];
$user_address = $data['address'];
$user_notification = $data['notification'];
$user_gender = $data['gender'];
$user_accept_mailing = $data['accept_mailing'];

?>
<div>
    
    <a href="/">
        <div class="header2 bg-saumon flex center">
            <img class="fleche-gauche" src="/images/fleche-gauche-blanche.png" alt="">
            <h2 class="blanc margin-right">Mon compte</h2>
        </div>
    </a>
    <a href="/?logout">
        <div class="header2 bg-noir flex center">
            <h2 class="blanc margin-right">Déconnexion</h2>
        </div>
    </a>

    <form class="flex column noir" method='post'>
        
        <h3>Mon profil</h3>
        
        
        <label for="name">Nom</label><input type='text' name='name' value='<?php print $user_name; ?>' required>
        <label for="firstname">Prénom</label><input type='text' name='firstname' value='<?php print $user_firstname; ?>' required>
        <label for="email">Email <?php if ($_SESSION['user_category']=='benevole') { ?><span class="saumon">*</span><?php } ?></label><input type='text' name='email' value='<?php print $user_email; ?>' <?php if ($_SESSION['user_category']=='benevole') print "required"; ?>>
        <label for="phone">Numéro de téléphone portable <?php if ($_SESSION['user_category']=='deloge') { ?><span class="saumon">*</span><?php } ?></label><input type='text' name='phone' value='<?php print $user_phonenumber; ?>'>
        <label for="arrondissement">Arrondissement (Marseille) <span class="saumon">*</span></label>
        <select name='arrondissement' required>
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
        <label for="address">Adresse (facultative)</label><input type='text' name='address' value='<?php print $user_address; ?>' placeholder="">
        <label for="gender">Genre (facultatif)</label>
        <section class="gender">
            <div>
                <input type="radio" id='homme' name="gender" value="homme" <?php if ($user_gender=='homme') print "checked"; ?>>
                <label for="homme">Homme</label>
            </div>
            <div>
                <input type="radio" id='femme' name="gender" value="femme" <?php if ($user_gender=='femme') print "checked"; ?>>
                <label for="femme">Femme</label>
            </div>
            <div>
                <input type="radio" id='nonbinaire' name="gender" value="nonbinaire" <?php if ($user_gender=='nonbinaire') print "checked"; ?>>
                <label for="nonbinaire">Non binaire</label>
            </div>
        </section>
        
        <h3>Identifiant / Mot de passe</h3>
        
        <label for="login">Identifiant <span class="saumon">*</span></label><input type='text' name='login' value='<?php print $user_login; ?>'>
        <label for="password">Nouveau mot de passe </label><input type='password' name='password' id="password">
        <input type="checkbox" value="Voir" id="viewPassword" onclick="togglePasswordView()">
        <label for="viewPassword">Voir</label>
        
        <h3>Mes notifications</h3>
        <label for="notifications">Notifications de la messagerie par&nbsp;:</label>
        <input type='checkbox' name="notif_email" value="accept" <?php if (($user_notification=='email')||($user_notification=='email+sms')) print "checked"; ?>> email
        <input type='checkbox' name="notif_sms" value="accept" <?php if (($user_notification=='sms')||($user_notification=='email+sms')) print "checked"; ?>> SMS<br><br>
        
            <label for="cgu"><input type="checkbox" name="cgu" value="accept" required> J'ai lu et j'accepte les <a class="small-text saumon" href="/cgu">CGU</a></label>
            <label><input type="checkbox" name="rgpd" value="accept" required> J'accepte que les informations saisies soient utilisées pour la gestion de l'application <strong>Aouf</strong></label>
            <label for=""><input type="checkbox" name="acceptinfos" value="accept" <?php if ($user_accept_mailing=='yes') print "checked"; ?>> J'accepte de recevoir des informations d'Aouf <span class="saumon">(optionnel)</span></label>

        <button class='bg-vert noir' type="submit" name="button" value="Modifier">Modifier</button>
    </form>
</div>
<!-- Placement de script temporaire : insérer à la fin de body -->
<script>
    function togglePasswordView() {
        var passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
</script>
<?php
require_once 'footer.php';
