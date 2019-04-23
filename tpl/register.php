<?php
require_once 'head.php';
?>
<body>
    <div class='home flex column center bg-noir'>
        <div class="content flex column center">
                <div id='logo-accueil-deco'></div>
                <div class="formulaires flex column center">
                    <form class="flex column center" action="/deloge/register" method="post">
                        <button class='bg-vert' type="submit"value='Connexion'>Je suis délogé(e)</button>
                    </form>
                    <form class="flex column center" action="/benevole/register" method="post">
                        <button class='bg-saumon' type='submit' value='Connexion'>Je veux aider</button>
                    </form>
                    <center><a class="small-text" href='/'>Retour</a></center
                </div>
        </div>
    </div>
<?php 
    require_once 'footer.php';
