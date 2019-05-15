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
    <div class='container no-margin full-size noir'>
        <div class="titre bg-saumon blanc">
            <h2>Compte Délogé <br><span class="small-text">(demande de compte)</span></h2>
        </div>
        <center><a class="small-text under" href='/register'>Retour</a></center>
        <form class='full-size flex center column' method='post'>
            <label for="username">Identifiant <span class="saumon">*</span></label>
            <input type='username' name='username' id='username' required>
            <label for="password">Mot de passe <span class="saumon">*</span></label>
            <input type='password' name='password' id="password" required>
            <input type="checkbox" value="Voir" id="viewPassword" onclick="togglePasswordView()">
            <label for="viewPassword">Voir</label>

            <label for="nom">Nom <span class="saumon">*</span></label>
            <input type='text' name='nom' required>
            <label for="prenom">Prénom <span class="saumon">*</span></label>
            <input type='text' name='prenom' required>
            <label for="phone">Numéro de téléphone portable <span class="saumon">*</span></label>
            <input type='text' name='phone' required>
            <label for="email">Email</label>
            <input type='text' name='email'>
            <label for="">Arrondissement (Marseille) <span class="saumon">*</span></label>
            <select name='arrondissement' required>
                <option value='0' selected='selected'>--</option>
                <option value='1'>Marseille 1er</option>
                <option value='2'>Marseille 2eme</option>
                <option value='3'>Marseille 3eme</option>
                <option value='4'>Marseille 4eme</option>
                <option value='5'>Marseille 5eme</option>
                <option value='6'>Marseille 6eme</option>
                <option value='7'>Marseille 7eme</option>
                <option value='8'>Marseille 8eme</option>
                <option value='9'>Marseille 9eme</option>
                <option value='10'>Marseille 10eme</option>
                <option value='11'>Marseille 11eme</option>
                <option value='12'>Marseille 12eme</option>
                <option value='13'>Marseille 13eme</option>
                <option value='14'>Marseille 14eme</option>
                <option value='15'>Marseille 15eme</option>
                <option value='16'>Marseille 16eme</option>
            </select>
            <label for="">Hôtel</label>
            <select name='hotel'>
                <option value='0' selected='selected'>--</option>
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
            <input type='text' name='address'>
            <section class="gender">
                <label for="gender">Genre (facultatif)</label>
                <div>
                    <input type="radio" id='homme' name="gender" value="homme">
                    <label for="homme">Homme</label>
                </div>
                <div>
                    <input type="radio" id='femme' name="gender" value="femme">
                    <label for="femme">Femme</label>
                </div>
                <div>
                    <input type="radio" id='nonbinaire' name="gender" value="nonbinaire">
                    <label for="nonbinaire">Non binaire</label>
                </div>
            </section>
            <input type="checkbox" value="" id="" required> J'accepte les CGU <span class="saumon">*</span>
            <input type="checkbox" value="" id="" required> J'accepte XXX <span class="saumon">*</span>
            <input type="checkbox" value="" id="" required> J'accepte YYY <span class="saumon">*</span>
            <input type='hidden' name='category' value='deloge'>
            <button class='bg-saumon blanc' type="submit" value="S'enregistrer">S'enregistrer</button>
        </form>
    </div>
    <!-- Placement de script temporaire : insérer à la fin de body -->
    <script>
        function togglePasswordView() {
            var passwordInput = document.getElementById("password");
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        }
    </script>

<script type="text/javascript" src="/js/ytmenu.js"></script>
</body>
