<?php
require_once 'head.php';

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
    if (($email != null)&&(!filter_var($email, FILTER_VALIDATE_EMAIL))) { print "<div class='erreur noir bg-saumon center'>Erreur, adresse email invalide&nbsp;!</div>"; goto skip; }
    $req = "SELECT COUNT(*) FROM users WHERE email='$email' LIMIT 1";
    $statement = $pdo->query($req);
    if (($email != null)&&($statement->fetchColumn()>0)) { print "<div class='erreur noir bg-saumon center'>Erreur, adresse email déjà utilisée&nbsp;!</div>"; goto skip; }
    $phone = ($_POST['phone'] != "") ? strip_tags($_POST['phone']) : null;
    if ($phone == null) { print "<div class='erreur noir bg-saumon center'>Erreur, vous devez avoir un numéro de téléphone&nbsp;!</div>"; goto skip; }
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
    $category = "deloge";
    $hotel = ((isset($_POST['hotel']))&&($_POST['hotel']!="0")) ? strtolower(strip_tags($_POST['hotel'])) : null;
    if (($hotel != null)&&(!ctype_alpha($hotel))) { print "<div class='erreur noir bg-saumon center'>Erreur, nom d'hôtel invalide&nbsp;!</div>"; goto skip; }

    $req = "INSERT INTO users(login,category,status,email,phonenumber,name,firstname,gender,arrondissement,address,password,notification,accept_mailing,hotel) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($req);
    if ($statement->execute([$login,$category,'unvalidated',$email,$phone,$name,$firstname,$gender,$arrondissement,$address,$password,'sms',$acceptinfos,$hotel])) {

        // send email to 5 nov : TODO
        echo "Compte <strong>$login</strong> en cours d'enregistrement&nbsp;!<br>";
        echo "<div class='erreur noir bg-saumon center'>Compte <strong>$login</strong> en cours d'enregistrement&nbsp;!<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";

    } else {
        echo "<div class='erreur noir bg-saumon center'>Erreur : identifiant ou email déjà existant, ou autre erreur...<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";
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
            <h2>inscription délogé(é)</h2>
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
            <label for="phone">Téléphone portable</label>
            <input type='text' name='phone' placeholder="0612345678" required>
            <label for="email">e-mail <span class="saumon">(optionnel)</span></label>
            <input type='text' name='email' placeholder="votre-email@example.com">
            <label for="">Arrondissement (Marseille)</label>
            <select name='arrondissement' required>
                <option value='0' selected='selected' disabled='disabled'>Votre arrondissement actuel</option>
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
            <label for="hotel">Hôtel <span class="saumon">(optionnel)</span></label>
            <select name='hotel'>
                <option value='0' selected='selected'>Hôtel</option>
                <option value='no'>Non logé dans un hôtel</option>
                <option value='roosevelt'>Hôtel Roosevelt (Marseille 1er)</option>
                <option value='ibissaintcharles'>IBIS St Charles (Marseille 1er)</option>
                <option value='apparthotelportedaix'>Appart Hôtel Porte d’Aix (Marseille 1er)</option>
                <option value='toyokoinn'>Toyoko Inn (Marseille 3ème)</option>
                <option value='bbhoteljoliette'>BB Hôtel Joliette (Marseille 2ème)</option>
                <option value='adagiojoliette'>ADAGIO Joliette (Marseille 2ème)</option>
                <option value='ibiscolbert'>IBIS Colbert (Marseille 2ème)</option>
                <option value='ibisjoliette'>IBIS Joliette (Marseille 2ème)</option>
                <option value='ibistimone'>IBIS Timone (Marseille 5ème)</option>
                <option value='ibisbudgettimone'>IBIS Budget Timone (Marseille 5ème)</option>
                <option value='odalyscanebiere'>ODALYS Canebière (Marseille 1er)</option>
                <option value='leryad'>LE RYAD (Marseille 1er)</option>
                <option value='residencepapere'>Résidence Papère (Marseille 1er)</option>
                <option value='novoteljoliette'>NOVOTEL Joliette (Marseille 2ème)</option>
                <option value='bbhoteltimone'>BB Hôtel Timone (Marseille 5ème)</option>
                <option value='autre'>Autre hôtel (à envoyer en feedback)</option>
            </select>
            <label for="address">Adresse <span class="saumon">(optionnel)</span></label>
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
                J'accepte de recevoir des informations d'Aouf <span class="saumon">(optionnel)</span></label>
            </section>

            <center>
                <button id="registerButton" class='bg-saumon blanc' type="submit" value="S'enregistrer">S'enregistrer</button>
            </center>
        </form>
    </div>
    <center>
        <a class="small-text under saumon return-bottom" href="/register">Retour</a>
    </center>
</body>
<!-- Placement de script temporaire : insérer à la fin de body -->
<script>
    function togglePasswordView() {
        var passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
</script>

<script type="text/javascript" src="/js/ytmenu.js"></script>
</body>
