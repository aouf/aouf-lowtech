<?php
require_once 'head.php';
// require_once 'header.php';
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

if (isset($_POST['username'])) {
    $login = $_POST['username'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $arrondissement = $_POST['arrondissement'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $category = $_POST['category'];

    $req = "INSERT INTO users(login,category,status,email,phonenumber,name,firstname,gender,arrondissement,address,password) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$login,$category,'unvalidated',$email,$phone,$nom,$prenom,$gender,$arrondissement,$address,$password]);

    // send email to 5 nov : TODO
    echo "Compte <strong>$login</strong> en cours d'enregistrement&nbsp;!<br>";
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
    <div class='container no-margin full-size noir flex center'>
        <form id="registerForm" class='full-size flex column' method='post'>
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
            <label for="phone">Téléphone portable</label>
            <input type='text' name='phone' placeholder="0675342199" required>
            <label for="email">e-mail <span class="saumon">(optionnel)</span></label>
            <input type='text' name='email' placeholder="david.pernot86@gmail.com">
            <label for="">Arrondissement (Marseille)</label>
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
            <label for="hotel">Hôtel <span class="saumon">(optionnel)</span></label>
            <select name='hotel'>
                <option value='0' selected='selected'>Hôtel</option>
                <option value='no'>Non logé dans un hôtel</option>
                <option value=''>Hôtel Roosevelt</option>
                <option value=''>IBIS St Charles</option>
                <option value=''>Appart Hôtel Porte d’Aix</option>
                <option value=''>Toyoko Inn</option>
                <option value=''>BB Hôtel Joliette</option>
                <option value=''>ADAGIO Joliette</option>
                <option value=''>IBIS Colbert</option>
                <option value=''>IBIS Joliette</option>
                <option value=''>IBIS Timone</option>
                <option value=''>IBIS Budget Timone</option>
                <option value=''>ODALIS Canebière</option>
                <option value=''>RYAD</option>
                <option value=''>Résidence Papère</option>
                <option value=''>NOVOTEL Joliette</option>
                <option value=''>BB Hôtel Timone</option>
                <option value=''>Autre hôtel</option>
            </select>
            <label for="address">Adresse</label>
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
            <label for="cgu"><input type="checkbox" value="" id="" name="cgu" required> J'ai lu et j'accepte les <a class="small-text saumon" href="/cgu">CGU</a></label>
            <label for=""><input type="checkbox" value="" id="" required> J'accepte XXX</label>
            <label><input type="checkbox" value="" id="" required> J'accepte YYY</label>
            <input type='hidden' name='category' value='deloge'>
            <button class='bg-saumon blanc' type="submit" value="S'enregistrer">S'enregistrer</button>
        </form>
    </div>
    <center><a class="small-text under saumon" href="/register">Retour</a></center>
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
