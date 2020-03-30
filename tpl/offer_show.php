<?php
require_once 'head.php';
require_once 'header.php';

if (($_SESSION['user_category']!='admin')&&($_SESSION['user_category']!='benevole')&&($_SESSION['user_category']!='deloge')) {
    die("permission denied");
}

$pdo = new PDO('mysql:host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

$user_id = $_SESSION['user_id'];

$uri = $_SERVER['REQUEST_URI'];
preg_match('#^/offer/show/(\d+)/(\d+)$#', $uri, $matches);
$offer_id = (int)$matches[1];
$with_id = (int)$matches[2];
if (!($offer_id>0)) {
    echo "Erreur, offre inexistante";
    die();
}
if (!($with_id>0)) {
    echo "Erreur, user inexistant";
    die();
}

if (isset($_POST['message'])) {
    $message = $_POST['message'];
    $to_id = $_POST['to'];
    $req = "INSERT INTO messages(offer_id,from_id,to_id,message) VALUES (?,?,?,?)";
    $statement = $pdo->prepare($req);
    $statement->execute([$offer_id, $user_id, $to_id, $message]);

    // send email for notification
    $req = "SELECT email,phonenumber,notification FROM users WHERE id=$to_id LIMIT 1";
    $statement = $pdo->query($req);
    $data = $statement->fetch();
    $email_addr = $data['email'];
    $phone_number = $data['phonenumber'];
    $notification = $data['notification'];
    if (($notification == 'email')&&($email_addr != '')) {
        $headers_mail = "MIME-Version: 1.0\n";
        $headers_mail .= 'From: Aouf <'.$conf['mail']['from'].">\n";
        $headers_mail .= 'Content-Type: text/plain; charset="utf-8"'."\n";
        $body_mail = "Bonjour,

Vous avez reçu un nouveau message via AOUF :

$message

Pour répondre :
https://beta.aouf.fr/message/list

-- 
L'equipe Aouf
";
        mail($email_addr,'Nouveau message Aouf',$body_mail,$headers_mail);
    }

    // Notification SMS
    if ((($notification == 'sms')||($notification == 'email+sms'))&&($phone_number != '')) {
        $body_sms = 'Nouveau+message+via+AOUF+:+https://beta.aouf.fr/message/list';
        $ch = curl_init("https://api.smsmode.com/http/1.6/sendSMS.do?accessToken=".$conf['sms']['smsmodeapikey']."&message=".$body_sms."&numero=$phone_number");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($ch);
        curl_close($ch);
    }
    
   // redirect to discuss
   header("Location: /message/write/$offer_id/$with_id/");
   exit;
}


$req = "SELECT * FROM offers where id = $offer_id LIMIT 1";
$statement = $pdo->query($req);
$data = $statement->fetch();
$offer_title = $data['title'];
$offer_description = $data['description'];
$offer_userid = $data['user_id'];
$offer_arrondissement = $data['arrondissement'];
$offer_arrondissement == '1' ? $offer_arrondissement = $offer_arrondissement.'er arrondissement' : $offer_arrondissement = $offer_arrondissement.'ème arrondissement' ;
$offer_address = $data['address'];
$offer_date_start = $data['date_start'];
$offer_date_end = $data['date_end'];
$categorie = $data['category'];
$offer_type = $data['offer_type'];

$req = "SELECT * FROM users WHERE id=$with_id LIMIT 1";
$statement = $pdo->query($req);
$data1 = $statement->fetch();

$prenom = ucfirst($data1['firstname']);
$nom = strtoupper(substr($data1['name'], 0, 1)).'.';
$nomComplet = $prenom.' '.$nom;

//$req = "SELECT * FROM messages WHERE offer_id = $offer_id AND ( ( from_id=$user_id AND to_id=$with_id ) OR ( from_id=$with_id AND to_id=$user_id ) )";
//$statement = $pdo->query($req);

if ($offer_type=='offer') {
    print "<a href='/offer/list/$categorie'>";
} else {
    print "<a href='/offer/yourlist'>";
}
?>
    <div class="header2 bg-blanc flex center">
        <img class="fleche-gauche" src="/images/fleche-gauche-saumon.png" alt="">
        <h2 class="saumon margin-right"><?php echo $offer_title ; ?></h2>
    </div>
</a>
<div class='background-noir-offre bg-noir'>
    <div class="background-blanc-offre bg-blanc">
        <h3 class="noir">Par <?php echo $nomComplet; ?></h3>
        <p class="saumon"><image class='ico-mini' src='/images/horloge.png' /> <?php echo $offer_date_start ; ?> - <?php echo $offer_date_end ; ?></p>
        <p class="saumon"><image class='ico-mini' src='/images/localisation.png' /> <?php echo $offer_arrondissement; echo $offer_adress != NULL ? ' - '. $offer_adress: ""; ?></p>
        <?php 
            if ($data['picture'] != 'NULL') {
                $picture = base64_encode($data['picture']);
                ?>
                <div class="image-offre" style='background-image: url("data:image/jpg;base64,<?php echo $picture; ?>")'></div>
                <?php
            }
        ?>
        <p class="noir"><?php echo $offer_description; ?></p>
        <!--<p><a class="small-text saumon" href='/report'><image class='ico-mini' src='/images/attention.png' /> <span class="under">Signaler un problème</span></a></p>-->
    </div>

<div class="header2 bg-saumon flex center">
    <h2 class="blanc">Message privé pour <?php echo $nomComplet; ?></h2>
</div>

<section class="message-form bg-saumon">
    <form id="sendMessageForm" class="flex center" method='post'>
        <input type='hidden' name='to' value='<?php echo $with_id; ?>'>
        <section class="relative">
            <textarea class='message-input bg-blanc' name='message'></textarea>
            <button class="submit-message" type='submit' value='Ok'>ok</button>
        </section>
    </form>
</section>

<section class="conversation bg-blanc">
<p><a class="small-text saumon" href='/report'><image class='ico-mini' src='/images/attention.png' /> <span class="under">Signaler un problème</span></a></p>
</section>

<?php
require_once 'footer.php';
