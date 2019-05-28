<?php

define('SERVEUR', '127.0.0.1');
define('SERVEURPORT', 3306);
define('BASE', 'aouf');
define('NOM', 'aouf');
define('PASSE', 'PASSWORD');
define('DRIVER', 'mysql');

$pdo = new PDO(DRIVER.':host='.SERVEUR.';dbname='.BASE,NOM,PASSE);

