<?php
require_once 'head.php';

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/validation/(\w+)$#', $uri, $matches);
$token = $matches[1];

if (isset($token)) {
    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);
    // TODO: à sécuriser contre les XSS
    $req = "SELECT id,status FROM users WHERE create_token='$token' LIMIT 1";
    $statement = $pdo->query($req);
    $data = $statement->fetch();

    $status = $data['status'];
    $moderation_id = $data['id'];
    
    if ($status == 'unvalidated') {

        $req = "UPDATE users set status='enabled',create_token=NULL WHERE id = $moderation_id";
        $statement = $pdo->prepare($req);
        $statement->execute();

        echo "<div class='erreur noir bg-saumon center'>Compte activé&nbsp;!</div>";
    } else {
        echo "<div class='erreur noir bg-saumon center'>Erreur de validation&nbsp;!</div>";
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
