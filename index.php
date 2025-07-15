<?php
namespace App;



define('DS', DIRECTORY_SEPARATOR); // le caractère séparateur de dossier (/ ou \)
// meilleure portabilité sur les différents systêmes.
define('BASE_DIR', dirname(__FILE__).DS); // pour simplifier la recherche au niveau de la racine.
define('VIEW_DIR', BASE_DIR."view/");   //le chemin où se trouvent les vues
define('PUBLIC_DIR', "public/");     //le chemin où se trouvent les fichiers publics (CSS, JS, IMG)

define('DEFAULT_CTRL', 'Home');//nom du contrôleur par défaut
define('ADMIN_MAIL', "admin@gmail.com");//mail de l'administrateur

require("app/Autoloader.php");

Autoloader::register();

//démarre une session ou récupère la session actuelle
session_start();
require_once 'app/helpers.php';
// Gestion personnalisée de l'historique de navigation
$currentPage = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
}

if (empty($_SESSION['history']) || end($_SESSION['history']) !== $currentPage) {
    $_SESSION['history'][] = $currentPage;
}

// Fonction pour récupérer la page précédente dans l'historique
function getPreviousPage() {
    if (count($_SESSION['history']) > 1) {
        array_pop($_SESSION['history']);
        return end($_SESSION['history']);
    }
    return 'index.php';
}

//et on intègre la classe Session qui prend la main sur les messages en session
use App\Session as Session;

//---------REQUETE HTTP INTERCEPTEE-----------
$ctrlname = DEFAULT_CTRL;//on prend le controller par défaut
//ex : index.php?ctrl=home
if(isset($_GET['ctrl'])){
    $ctrlname = $_GET['ctrl'];
}
//on construit le namespace de la classe Controller à appeller
$ctrlNS = "Controller\\".ucfirst($ctrlname)."Controller";
//on vérifie que le namespace pointe vers une classe qui existe
if(!class_exists($ctrlNS)){
    //si c'est pas le cas, on choisit le namespace du controller par défaut
    $ctrlNS = "Controller\\".DEFAULT_CTRL."Controller";
}
$ctrl = new $ctrlNS();

$action = "index";//action par défaut de n'importe quel contrôleur
//si l'action est présente dans l'url ET que la méthode correspondante existe dans le ctrl
if(isset($_GET['action']) && method_exists($ctrl, $_GET['action'])){
    //la méthode à appeller sera celle de l'url
    $action = $_GET['action'];
}
if(isset($_GET['id'])){
    $id = $_GET['id'];
}
else $id = null;
//ex : HomeController->users(null)
$result = $ctrl->$action($id);
if (!is_array($result)) {
    die("❌ La méthode $action() du contrôleur $ctrlNS ne retourne pas de tableau.");
}


/*--------CHARGEMENT PAGE--------*/
if($action == "ajax"){ //si l'action était ajax
    //on affiche directement le return du contrôleur (càd la réponse HTTP sera uniquement celle-ci)
    echo json_encode($result);
}
else{
    ob_start();//démarre un buffer (tampon de sortie)
    $meta_description = $result['meta_description'];
    /* la vue s'insère dans le buffer qui devra être vidé au milieu du layout */
    include($result['view']);
    /* je place cet affichage dans une variable */
    $page = ob_get_contents();
    /* j'efface le tampon */
    ob_end_clean();
    /* j'affiche le template principal (layout) */
    include VIEW_DIR."layout.php";
}
