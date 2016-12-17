<?php
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';
require_once('libs/Smarty.class.php');

//Séléctionne l'ID de l'utilisateur en cours à partir de son SID en COOKIE
$sth = $bdd->prepare("SELECT id FROM utilisateurs WHERE sid = :sid");
$sth->bindValue(':sid', $_COOKIE['sid'], PDO::PARAM_STR);
$sth->execute();

$tab_id = $sth->fetchAll(PDO::FETCH_ASSOC);
$id = $tab_id[0]['id'];

//MAJ de son SID à 0 afin de le déconncter du site
$sth2 = $bdd->prepare("UPDATE utilisateurs SET sid = :sid WHERE id = :id");
$sth2->bindValue(':sid', 0, PDO::PARAM_STR);
$sth2->bindValue(':id', $id, PDO::PARAM_INT);
$sth2->execute();

//Renvoie sur la page Connexion
header("Location: connexion.php");
?>

