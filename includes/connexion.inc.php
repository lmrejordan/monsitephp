<?php

require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';

session_start();

//Déclare la variable COOKIE
setcookie('connecte');

//Vérification de la présence du cookie et de sa conformité

if(isset($_COOKIE['sid']) && !empty($_COOKIE['sid']))
{
    $sth = $bdd->prepare("SELECT * FROM utilisateurs WHERE sid = :sid");
    $sth->bindValue(':sid', $_COOKIE['sid'], PDO::PARAM_INT);
    $sth->execute();
    
    $count = $sth->rowCount();
    
    if($count>0)
    {
        $_COOKIE['connecte'] = TRUE;
    }
    else
    {
        $_COOKIE['connecte'] = FALSE;
    }
}

