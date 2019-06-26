<?php
require_once 'head.php';
// require_once 'header.php';
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

if (isset($_POST['login'])) {
    $login = strtolower(strip_tags($_POST['login']));
    if (!ctype_alnum($login)) { print "<div class='erreur noir bg-saumon center'>Erreur, identifiant invalide&nbsp;!</div>"; goto skip; }
    if (strlen($login)<3) { print "<div class='erreur noir bg-saumon center'>Erreur, identifiant trop court&nbsp;!</div>"; goto skip; }
    $req = "SELECT COUNT(*) FROM users WHERE login='$login' LIMIT 1";
    $statement = $pdo->query($req);
    if ($statement->fetchColumn()>0) { print "<div class='erreur noir bg-saumon center'>Erreur, identifiant déjà utilisé&nbsp;!</div>"; goto skip; }
    $name = strip_tags($_POST['name']);
    if (!preg_match("/^([\p{L}-' ]+)$/u", $name)) { print "<div class='erreur noir bg-saumon center'>Erreur, nom invalide&nbsp;!</div>"; goto skip; }
    if (strlen($name)<1) { print "<div class='erreur noir bg-saumon center'>Erreur, nom trop court&nbsp;!</div>"; goto skip; }
    $firstname = strip_tags($_POST['firstname']);
    if (!preg_match("/^([\p{L}-' ]+)$/u", $firstname)) { print "<div class='erreur noir bg-saumon center'>Erreur, prénom invalide&nbsp;!</div>"; goto skip; }
    if (strlen($firstname)<1) { print "<div class='erreur noir bg-saumon center'>Erreur, prénom trop court&nbsp;!</div>"; goto skip; }
    $email = ($_POST['email'] != "") ? strtolower(strip_tags($_POST['email'])) : null;
    if ($email == null) { print "<div class='erreur noir bg-saumon center'>Erreur, vous devez avoir une adresse email&nbsp;!</div>"; goto skip; }
    if (($email != null)&&(!filter_var($email, FILTER_VALIDATE_EMAIL))) { print "<div class='erreur noir bg-saumon center'>Erreur, adresse email invalide&nbsp;!</div>"; goto skip; }
    $req = "SELECT COUNT(*) FROM users WHERE email='$email' LIMIT 1";
    $statement = $pdo->query($req);
    if (($email != null)&&($statement->fetchColumn()>0)) { print "<div class='erreur noir bg-saumon center'>Erreur, adresse email déjà utilisée&nbsp;!</div>"; goto skip; }
    $phone = ($_POST['phone'] != "") ? strip_tags($_POST['phone']) : null;
    if (($phone != null)&&(!preg_match("/^([\d\.+\-\(\) ]+)$/", $phone))) { print "<div class='erreur noir bg-saumon center'>Erreur, numéro de téléphone invalide&nbsp;!</div>"; goto skip; }
    if (($phone != null)&&(strlen($phone)<7)) { print "<div class='erreur noir bg-saumon center'>Erreur, numéro de téléphone trop court&nbsp;!</div>"; goto skip; }
    $req = "SELECT COUNT(*) FROM users WHERE phonenumber='$phone' LIMIT 1";
    $statement = $pdo->query($req);
    if (($phone != null)&&($statement->fetchColumn()>0)) { print "<div class='erreur noir bg-saumon center'>Erreur, numéro de téléphone déjà utilisé&nbsp;!</div>"; goto skip; }
    $arrondissement = $_POST['arrondissement'];
    if (!ctype_digit($arrondissement)) { print "<div class='erreur noir bg-saumon center'>Erreur, arrondissement invalide&nbsp;!</div>"; goto skip; }
    $address = ($_POST['address'] != "") ? strip_tags($_POST['address']) : null;
    if (($address != null)&&(!ctype_print($address))) { print "<div class='erreur noir bg-saumon center'>Erreur, adresse invalide&nbsp;!</div>"; goto skip; }
    $gender = (($_POST['gender'] != "")&&($_POST['gender'] != "homme")&&($_POST['gender'] != "femme")&&($_POST['gender'] != "nonbinaire")) ? strip_tags($_POST['gender']) : null;
    if ((isset($_POST['notif_email']))&&(isset($_POST['notif_sms']))) $notification = "email+sms";
    if ((isset($_POST['notif_email']))&&(!isset($_POST['notif_sms']))) $notification = "email";
    if ((isset($_POST['notif_sms']))&&(!isset($_POST['notif_email']))) $notification = "sms";
    if ((!isset($_POST['notif_email']))&&(!isset($_POST['notif_sms']))) $notification = "no";
    if (isset($_POST['acceptinfos'])) $acceptinfos = 'yes'; else $acceptinfos = 'no';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $category = "benevole";
    $token = sha1(random_bytes(128));

    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);

    $req = "INSERT INTO users(login,category,status,email,phonenumber,name,firstname,gender,arrondissement,address,password,create_token,notification,accept_mailing) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($req);
    if ($statement->execute([$login,$category,'unvalidated',$email,$phone,$name,$firstname,$gender,$arrondissement,$address,$password,$token,'email',$acceptinfos])) {

        // send email for validation
        $headers_mail = "MIME-Version: 1.0\n";
        $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
        $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
        $body_mail = "Bonjour,

Validez votre compte en cliquant sur ce lien :

https://beta.aouf.fr/validation/$token

(valable pendant 24h)

--
L'equipe Aouf
";
        mail($email,'Validation de votre compte Aouf',$body_mail,$headers_mail);
        // send email notification
        $body_mail = "Bonjour,

Ajout du compte bénévole $firstname $name ($login) en cours.
Rien n'est à faire, il doit valider son compte via son email.

--
L'equipe Aouf
";
        mail($conf['mail']['admin'],'[aouf] Ajout compte benevole en cours',$body_mail,$headers_mail);

        echo "<div class='erreur noir bg-saumon center'>Compte <strong>$login</strong> en cours de création, vous allez recevoir un email pour validation&nbsp;!<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";
    } else {
        echo "<div class='erreur noir bg-saumon center'>Erreur à la création...<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";
    }

skip:
}
?>
<body>
    <header class="bg-noir flex">
        <section>
            <a href="/accueil">
                <div class="logo-menu"></div>
            </a>
        </section>
        <div class="titre saumon">
            <h2>inscription bénévole</h2>
        </div>
    </header>
    <div class='container no-margin full-size noir center'>
        <form id="registerForm" class='full-size column' method='post'>
            <label for="login">Identifiant (uniquement des lettres ou chiffres)</label>
            <input type='username' name='login' id='login' placeholder="prenomnom" required>

            <section>
                <label for="password">Mot de passe</label>
                <input class="password" type='password' name='password' id="password" placeholder="********" required>
                <button class="unmask" type="button" id="viewPassword" title="Mask/Unmask password to check content" onclick="togglePasswordView()">Unmask</button>
            </section>

            <label for="nom">Nom</label>
            <input type='text' name='name' placeholder="Votre nom" required>
            <label for="prenom">Prénom</label>
            <input type='text' name='firstname' placeholder="Votre prénom" required>
            <label for="email">e-mail</label>
            <input type='text' name='email' placeholder="votre-email@example.com" required>
            <label for="phone">Téléphone portable <span class="saumon">(optionnel)</span></label>
            <input type='text' name='phone' placeholder="0612345678">
            <label for="">Arrondissement (Marseille) où je peux offrir des services</label>
            <select name='arrondissement' required>
                <option value='0' selected='selected' disabled='disabled'>Votre arrondissement</option>
                <option value='1'>Marseille 1er</option>
                <option value='2'>Marseille 2ème</option>
                <option value='3'>Marseille 3ème</option>
                <option value='4'>Marseille 4ème</option>
                <option value='5'>Marseille 5ème</option>
                <option value='6'>Marseille 6ème</option>
                <option value='7'>Marseille 7ème</option>
                <option value='8'>Marseille 8ème</option>
                <option value='9'>Marseille 9ème</option>
                <option value='10'>Marseille 10ème</option>
                <option value='11'>Marseille 11ème</option>
                <option value='12'>Marseille 12ème</option>
                <option value='13'>Marseille 13ème</option>
                <option value='14'>Marseille 14ème</option>
                <option value='15'>Marseille 15ème</option>
                <option value='16'>Marseille 16ème</option>
            </select>

            <label for="address" >Adresse où je peux offrir des services <span class="saumon">(optionnel)</span></label>
            <input type='text' name='address' placeholder="">
            <label for="gender">Genre <span class="saumon">(optionnel)</span></label>
            <section class="gender">
                    <input type="radio" id='homme' name="gender" value="homme">
                    <label for="homme">H</label>
                    <input type="radio" id='femme' name="gender" value="femme">
                    <label for="femme">F</label>
                    <input type="radio" id='nonbinaire' name="gender" value="nonbinaire">
                    <label for="nonbinaire">Non binaire</label>
            </section>

            <section>
                <label class="checkboxLabel" for="cgu"><input type="checkbox" name="cgu" value="accept" required>
                J'ai lu et j'accepte les <a class="small-text saumon" href="/cgu">CGU</a></label>
            </section>
            <br>
            <section>
                <label class="checkboxLabel" for="rgpd"><input type="checkbox" name="rgpd" value="accept" required>
                J'accepte que les informations saisies soient utilisées pour la gestion de l'application <strong>Aouf</strong></label>
            </section>
            <br>
            <section>
                <label class="checkboxLabel" for="acceptinfos"><input type="checkbox" name="acceptinfos" value="accept">
                J'accepte de recevoir des informations sur les annonces au sein de l'application et des nouvelles du projet Aouf <span class="saumon">(optionnel)</span></label>
            </section>

            <center>
                <button id="registerButton" class='bg-saumon blanc' type="submit" value="S'enregistrer">S'enregistrer</button>
            </center>
        </form>
</div>
<center><a class="small-text under saumon return-bottom" href="/register">Retour</a></center>
<!-- Placement de script temporaire : insérer à la fin de body -->
<script>
    function togglePasswordView() {
        var passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
</script>

<script type="text/javascript" src="/js/ytmenu.js"></script>
</body>
