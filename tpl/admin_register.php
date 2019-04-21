<?php

if ($_SESSION['user_category']!='admin') {
    die("permission denied");
}

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
    $type = $_POST['type'];
    
    $req = "INSERT INTO users(login,category,status,email,phonenumber,name,firstname,gender,address,password) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$login,$type,'enabled',$email,$phone,$nom,$prenom,$gender,$adresse,$password]);

    if ($type == 'benevole') {
    // send email for validation
    } elseif ($type == 'deloge') {
    // send email to 5 nov
    }
    echo "Compte <strong>$login</strong> en cours d'enregistrement&nbsp;!<br>";
}
?>

<h2>Compte Délogé</h2>
<form method='post'>
Identifiant : <input type='text' name='login'>*<br>
Nom : <input type='text' name='nom'>*<br>
Prénom : <input type='text' name='prenom'>*<br>
Numéro de téléphone : <input type='text' name='phone'>*<br>
Email : <input type='text' name='email'><br>
Adresse : <input type='text' name='adresse'><br>
<input type='radio' name='gender' value='homme'>Homme
<input type='radio' name='gender' value='femme'>Femme
<input type='radio' name='gender' value='nonbinaire'>Non binaire
<input type='radio' name='gender' value='famille'>Famille
<input type='hidden' name='type' value='deloge'><br>
Mot de passe : <input type='text' name='password'>*<br>
<input type='submit' value="S'enregistrer">
</form>

<h2>Compte Bénévole</h2>
<form method='post'>
Identifiant : <input type='text' name='login'>*<br>
Nom : <input type='text' name='nom'>*<br>
Prénom : <input type='text' name='prenom'>*<br>
Email : <input type='text' name='email'>*<br>
Numéro de téléphone : <input type='text' name='phone'><br>
Adresse : <input type='text' name='adresse'><br>
<input type='radio' name='gender' value='homme'>Homme
<input type='radio' name='gender' value='femme'>Femme
<input type='radio' name='gender' value='nonbinaire'>Non binaire
<input type='radio' name='gender' value='famille'>Famille
<input type='hidden' name='type' value='benevole'><br>
Mot de passe : <input type='text' name='password'>*<br>
<input type='submit' value="S'enregistrer">
</form>

