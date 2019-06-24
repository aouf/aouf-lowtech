<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['description'])) {
    // éviter brute force bourrin (TODO: à améliorer)
    sleep(1);
    $description = $_POST['description'];

    // notification par email
    $headers_mail = "MIME-Version: 1.0\n";
    $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
    $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
    $body_mail = "Bonjour,

Besoin posté par l'utilisateur $user_id :

$description

--
L'equipe Aouf
";

    mail($conf['mail']['admin'],'[aouf] Besoin',$body_mail,$headers_mail);
    echo "<div class='erreur noir bg-saumon center'>Besoin envoyé, merci&nbsp;!</div>";

}
?>
<body>
    <div class='container no-margin full-size noir'>
        <div class="titre bg-vert noir">
            <h2>Besoin d'une annonce</h2>
        </div>
        <form class='full-size flex center column' method='post'>
            <label for="feedback">Ce dont j'ai besoin&nbsp;:</label>
            <textarea name='description' placeholder="Bonjour ! Je n'ai pas trouvé d'annonce correspondant à mon besoin, j'aimerai…" required></textarea>
            <button class='bg-vert noir' type="submit" value="Envoyer mon besoin">Envoyer mon besoin</button>
        </form>
    </div>
<script type="text/javascript" src="/js/ytmenu.js"></script>
</body>
