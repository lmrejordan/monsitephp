<?php

try {$bdd = new PDO('mysql:host=localhost;dbname=u314950188_lmrej;charset=utf8','u314950188_lmrej','letitinedu59');
       $bdd->exec("set names utf8");
       $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur : '.$e->getMessage());
}
