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
            <h2>Compte Délogé <br><span class="small-text">(soumis à modération)</span></h2>
        </div>
        <center><a class="small-text" href='/register'>Retour</a></center>
        <form class='full-size flex center column' method='post'>
            <label for="login">Identifiant <span class="saumon">*</span></label>
            <input type='text' name='login'>
            <label for="nom">Nom <span class="saumon">*</span></label>
            <input type='text' name='nom'>
            <label for="prenom">Prénom <span class="saumon">*</span></label>
            <input type='text' name='prenom'>
            <label for="phone">Numéro de téléphone <span class="saumon">*</span></label>
            <input type='text' name='phone'>
            <label for="email">Email</label>
            <input type='text' name='email'>
            <label for="arrondissement">Arondissement</label>
            <select name='arrondissement'>
            <option value='0'>-</option>
            <option value='1'>1er</option>
            <option value='2'>2eme</option>
            <option value='3'>3eme</option>
            <option value='4'>4eme</option>
            <option value='5'>5eme</option>
            <option value='6'>6eme</option>
            <option value='7'>7eme</option>
            <option value='8'>8eme</option>
            <option value='9'>9eme</option>
            <option value='10'>10eme</option>
            <option value='11'>11eme</option>
            <option value='12'>12eme</option>
            <option value='13'>13eme</option>
            <option value='14'>14eme</option>
            <option value='15'>15eme</option>
            <option value='16'>16eme</option>
            </select>
            <label for="address">Adresse</label>
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
            <input type='hidden' name='category' value='deloge'>
            <label for="password">Mot de passe <span class="saumon">*</span></label><input type='password' name='password'>
            <button class='bg-saumon blanc' type="submit" value="S'enregistrer">S'enregistrer</button>
        </form>
    </div>
<?php 
    require_once 'footer.php';
