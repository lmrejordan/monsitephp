<?php
require_once 'settings/bdd.inc.php';
require_once 'settings/init.inc.php';

/* Résultat : Page courante, Index de départ, index d'arrivée

Déclarer une variable qui contient le nombre d'articles qu'on souhaite afficher sur une page

Variable qui contient la page courante

$nb_total_page = ceil($total / $message_par_page);
 
<?php $page = $_GET['p'];?>

Calculer l'index de départ de la requête

Numéro de page - 1 * Nb d'articles par page

Calculer le nb de msg publiés dans la table
 
<?php ceil($nbarticles / $nombre_article) ?>

*/
?>

<?php
$page = isset($_GET['p']) ? $_GET['p'] : 1; //S'il n'existe pas de paramètre "p" dans l'URL, on le met d'office à 1

$nombre_article = 2; //Dérinit le nombre d'article par page

function returnIndex($nombre_article, $page){

    $debut = ($page-1) * $nombre_article;//Calcule l'index principal

    //Calcul des éléments
    return $debut;
}

$IndexDepart = returnIndex(2,10);

//-----------------------------------------//

echo "Page : $page <br>";

echo "Index de départ : $IndexDepart <br>";

$sql = $bdd->prepare("SELECT COUNT(*) as nbarticles FROM articles WHERE publie = :publie");//Préparation de la requête
$sql->bindValue(':publie', 1, PDO::PARAM_INT);//Enlève tout ce qui n'est pas numérique
$sql->execute(); // Execute la requête

$tab_articles = $sql->fetchAll(PDO::FETCH_ASSOC); // Crée un tableau avec les champs

//print_r($tab_articles);

$total_article = $tab_articles[0]['nbarticles']; //Recherche la donnée "nbarticles" dans le tableau 

echo "Total d'articles dans la base : ".$total_article."<br>";

$nb_page = ceil($total_article / $nombre_article);//Calcule le nombre de page qui sera créé pour afficher les articles

echo "Nombre de pages : ".$nb_page."<br><br><br>";

?>