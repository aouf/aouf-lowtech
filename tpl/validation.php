<?php
require_once 'head.php';
// require_once 'header.php';
$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/validation/(\w+)$#', $uri, $matches);
$token = $matches[1];

if (isset($token)) {
    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);
    // TODO: à sécuriser contre les XSS
    $req = "SELECT status FROM users WHERE create_token='$token' LIMIT 1";
    $statement = $pdo->query($req);
    $data = $statement->fetch();

    $status = $data['status'];
    
    if ($status == 'unvalidated') {

        $req = "UPDATE users set status='enabled' WHERE id = $moderation_id";
        $statement = $pdo->prepare($req);
        $statement->execute();

        echo "Compte activé&nbsp;!<br>";
    } else {
        echo "Erreur de validation&nbsp;!<br>";
    }

}
?>
<body>
    <div class='container no-margin full-size noir'>
        <div class="titre bg-vert noir">
            <h2>Validation</h2>
        </div>
        <center><a class="small-text" href='/'>Retour</a></center>
</div>
<?php 
    require_once 'footer.php';
