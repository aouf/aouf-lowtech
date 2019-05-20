<?php
require_once 'head.php';
// require_once 'header.php';
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

if (isset($_POST['login'])) {
    $login = $_POST['login'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $phone = $_POST['phone'];
    $email_addr = $_POST['email'];
    $arrondissement = $_POST['arrondissement'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $category = $_POST['category'];
    $token = sha1(random_bytes(128));

    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);

    $req = "INSERT INTO users(login,category,status,email,phonenumber,name,firstname,gender,arrondissement,address,password,create_token,notification) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($req);
    if ($statement->execute([$login,$category,'unvalidated',$email_addr,$phone,$nom,$prenom,$gender,$arrondissement,$address,$password,$token,'email'])) {

        // send email for validation
        $headers_mail = "MIME-Version: 1.0\n";
        $headers_mail .= 'From: '.$conf['mail']['from']."\n";
        $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
        $body_mail = "Bonjour,

Validez votre compte en cliquant sur ce lien :

https://low.aouf.fr/validation/$token

(valable pendant 24h)

--
L'equipe Aouf
";
        mail($email_addr,'Validation de votre compte Aouf',$body_mail,$headers_mail);
        echo "<div class='erreur noir bg-saumon center'>Compte <strong>$login</strong> en cours de création, vous allez recevoir un email pour validation&nbsp;!<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";
    } else {
        echo "<div class='erreur noir bg-saumon center'>Erreur : identifiant ou email déjà existant, ou autre erreur...<br><a class='small-text under' href='/'>Retour à l'accueil</a></div>";
    }
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
            <label for="login">Identifiant</label>
            <input type='username' name='login' id='login' placeholder="davidpernot" required>

            <section>
                <label for="password">Mot de passe</label>
                <input class="password" type='password' name='password' id="password" placeholder="********" required>
                <button class="unmask" type="button" id="viewPassword" title="Mask/Unmask password to check content" onclick="togglePasswordView()">Unmask</button>
            </section>

            <label for="nom">Nom</label>
            <input type='text' name='nom' placeholder="Pernot" required>
            <label for="prenom">Prénom</label>
            <input type='text' name='prenom' placeholder="David" required>
            <label for="email">e-mail</label>
            <input type='text' name='email' placeholder="david.pernot86@gmail.com" required>
            <label for="phone">Téléphone portable <span class="saumon">(optionnel)</span></label>
            <input type='text' name='phone' placeholder="0675342199">
            <label for="">Arrondissement (Marseille) où je peux offrir des services</label>
            <select name='arrondissement' required>
                <option value='0' selected='selected'>Arrondissement</option>
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
            <input type='text' name='address' placeholder="13 rue Adolphe Thiers">
            <label for="gender">Genre <span class="saumon">(optionnel)</span></label>
            <section class="gender">
                    <input type="radio" id='homme' name="gender" value="homme">
                    <label for="homme">H</label>
                    <input type="radio" id='femme' name="gender" value="femme">
                    <label for="femme">F</label>
                    <input type="radio" id='nonbinaire' name="gender" value="nonbinaire">
                    <label for="nonbinaire">Non binaire</label>
            </section>
            <section class="flex">
                <input type="checkbox" value="" id="" name="cgu" required>
                <label for="cgu">J'ai lu et j'accepte les <a class="small-text saumon" href="/cgu">CGU</a></label>
            </section>
            <br>
            <section class="flex">
                <input type="checkbox" value="" id="myInfos" name="myInfos" required>
                <label for="myInfos">J'accepte que les informations saisies soient utilisées pour la gestion de l'application <strong>Aouf</strong></label>
            </section>
            <br>
            <section class="flex">
                <input type="checkbox" value="" id="aoufInfo" name="aoufInfo" required>
                <label for="aoufInfo"> J'accepte de recevoir des informations d'Aouf <span class="saumon">(optionnel)</span></label>
            </section>
            <input type='hidden' name='category' value='benevole'>
            <center>
                <button id="registerButton" class='bg-saumon blanc' type="submit" value="S'enregistrer">S'enregistrer</button>
            </center>
        </form>
</div>
<center><a class="small-text under saumon" href="/register">Retour</a></center>
<!-- Placement de script temporaire : insérer à la fin de body -->
<script>
    function togglePasswordView() {
        var passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
</script>

<script type="text/javascript" src="/js/ytmenu.js"></script>
</body>
