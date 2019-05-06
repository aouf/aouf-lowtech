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
    $statement->execute([$login,$category,'unvalidated',$email_addr,$phone,$nom,$prenom,$gender,$arrondissement,$address,$password,$token,'email']);

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
    echo "Compte <strong>$login</strong> en cours de création, vous allez recevoir un email pour validation&nbsp;!<br>";
}
?>
<body>
    <div class='container no-margin full-size noir'>
        <div class="titre bg-vert noir">
            <h2>Compte Bénévole</h2>
        </div>
        <center><a class="small-text under" href='/register'>Retour</a></center>
        <form class='full-size flex center column' method='post'>
            <label for="login">Identifiant <span class="saumon">*</span></label>
            <input type='username' name='login' id='login'>
            <label for="password">Mot de passe <span class="saumon">*</span></label>
            <input type='password' name='password'>
            <label for="nom">Nom <span class="saumon">*</span></label>
            <input type='text' name='nom'>
            <label for="prenom">Prénom <span class="saumon">*</span></label>
            <input type='text' name='prenom'>
            <label for="email">Email <span class="saumon">*</span></label>
            <input type='text' name='email'>
            <label for="phone">Numéro de téléphone portable</label>
            <input type='text' name='phone'>
            <label for="">Arrondissement (Marseille) où je peux offrir des services</label>
            <select name='arrondissement'>
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

            <label for="address">Adresse où je peux offrir des services</label>
            <input type='text' name='address'>
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
            </section>
            <input type='hidden' name='category' value='benevole'>
            <button class='bg-vert noir' type="submit" value="S'enregistrer">S'enregistrer</button>
        </form>
</div>
<?php 
    require_once 'footer.php';
