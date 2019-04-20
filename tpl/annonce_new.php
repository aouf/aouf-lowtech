<h2>Proposer une annonce</h2>

<form action='/annonce/create' method='post'>
Titre : <input type='text' name='titre'>*<br>
Désactivation de l'annonce le : <input type='text'><br>

<?php
$uri = $_SERVER['REQUEST_URI'];
if (preg_match('#^/annonce/new/restauration#', $uri)) {
    $type = 'restauration';
    print "Décrivez ce que vous proposer (pour combien de personnes ?
sans porc/végétarien/végétalien/hallal/casher/sans gluten ?
sur place ou à emporter ? etc.)
";
} elseif (preg_match('#^/annonce/new/blanchisserie#', $uri)) {
    $type = 'blanchisserie';
    print "Décrivez ce que vous proposer (lessive fournie ?
séchage sur place ? etc.)
";
}
?>
<br>
<textarea name='description'></textarea>*
<br><br>
<input type='hidden' name='type' value='<?php print $type; ?>'>
<input type='submit' value='Publier'>
</form>
