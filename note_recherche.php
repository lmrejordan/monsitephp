<?php

/* 
Champ de recherche :
 */
?>

 <form action="<?= $url_site?>/index.php" method="get" enctype="multipart/form-data" id="form_recherche">

        <div class="clearfix">
            <div class="input"><input type="text" name="recherche" id="recherche" placeholder="Votre recherche..."></div>
        </div>
     
        <div class="form-inline">
            <input type="submit" name="" value="Rechercher" class="btn btn-mini btn-primary">
        </div>
 </form>

<?php

/*
 * Vérifier dans l'URL la pésence du paramètre rechercher
 * Passe le / les terme(s) de recherche
 * Exécuter la requête
 * Compter les enregistrements
 * Su >0 on pousse le résultats dans un tableau
 * On gère l'affiche selon le résultat
 */

$sql = $bdd->prepare("SELECT * FROM articles WHERE titre LIKE :recherche OR texte LIKE :recherche");
$sth->bindValue(':recherche',"%$recherche", PDO::PARAM_STR);