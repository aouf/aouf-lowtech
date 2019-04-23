<?php
require_once 'head.php';
// require_once 'header.php';
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

if (isset($_POST['login'])) {
    $login = $_POST['login'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $category = $_POST['category'];
    
    $req = "INSERT INTO users(login,category,status,email,phonenumber,name,firstname,gender,address,password) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$login,$category,'unvalidated',$email,$phone,$nom,$prenom,$gender,$adresse,$password]);

    if ($category == 'benevole') {
    // send email for validation
    } elseif ($category == 'deloge') {
    // send email to 5 nov
    }
    echo "Compte <strong>$login</strong> en cours d'enregistrement&nbsp;!<br>";
}
?>
<body>
    <div class='container no-margin full-size'>
        <div class="titre bg-saumon">
            <h2>Compte Bénévole</h2>
        </div>
    <center><a class="small-text" href='/register'>Retour</a></center>
    <form class='full-size flex center column' method='post'>
        <label for="login">Identifiant <span class="saumon">*</span></label>
        <input type='text' name='login'>
        <label for="nom">Nom <span class="saumon">*</span></label>
        <input type='text' name='nom'>
        <label for="prenom">Prénom <span class="saumon">*</span></label>
        <input type='text' name='prenom'>
        <label for="email">Email <span class="saumon">*</span></label>
        <input type='text' name='email'>
        <label for="phone">Numéro de téléphone</label>
        <input type='text' name='phone'>
        <label for="adresse">Adresse</label>
        <input type='text' name='adresse'>
        <section class="gender">
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
            <div>
                <input type="radio" id='famille' name="gender" value="famille">
                <label for="famille">Famille</label>
            </div>
        </section>
        <input type='hidden' name='category' value='benevole'>
        <label for="password">Mot de passe <span class="saumon">*</span></label>
        <input type='password' name='password'>
        <input type='submit' value="S'enregistrer">
    </form>
</div>
<?php 
    require_once 'footer.php';
