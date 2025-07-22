<?php
namespace App; // Déclare l'espace de noms "App" pour ce fichier, utile pour l'autoloading et éviter les conflits de noms


// Définition de constantes utiles pour le projet
define('DS', DIRECTORY_SEPARATOR); // le caractère séparateur de dossier (/ ou \)
// meilleure portabilité sur les différents systêmes.
define('BASE_DIR', dirname(__FILE__).DS); // pour simplifier la recherche au niveau de la racine.
define('VIEW_DIR', BASE_DIR."view/");   //le chemin où se trouvent les vues
define('PUBLIC_DIR', "public/");     //le chemin où se trouvent les fichiers publics (CSS, JS, IMG)

define('DEFAULT_CTRL', 'Home');//nom du contrôleur par défaut
define('ADMIN_MAIL', "admin@gmail.com");//mail de l'administrateur

// Chargement automatique des classes
require("app/Autoloader.php"); //inclusion de l'autoloader maison
Autoloader::register(); // Enregistrement de l'autoloader PHP
use App\DAO;
DAO::connect(); // ✅ Connexion à la base de données


//démarre une session ou récupère la session actuelle
session_start();

require_once 'app/helpers.php'; // inclusion des fonctions d'aide (heplers.php)
// -------------------------------------------------------
// Gestion personnalisée de l'historique de navigation (stocké en session)
// -------------------------------------------------------
$currentPage = $_SERVER['REQUEST_URI']; // url actuelle visitée par l'utilisateur

if (!isset($_SESSION['history'])) { // si aucune session d'historique n'existe encore, on initialise un tableau vide
    $_SESSION['history'] = [];
}
// si l'historique est vide OU que la dernière page enrgistrée est différente de la page actuelle
if (empty($_SESSION['history']) || end($_SESSION['history']) !== $currentPage) {
    $_SESSION['history'][] = $currentPage; // On ajoute la page actuelle à l'historique
}

// Fonction utilitaire : retourne l'URL de la page précédente visitée
function getPreviousPage() {
    if (count($_SESSION['history']) > 1) {  // S'il y a plus d'une page dans l'historique, on peut retourner à la précédente
        array_pop($_SESSION['history']); // on retire la page actuelle
        return end($_SESSION['history']); // On retourne celle juste avant (la page précédente)

    }
    return 'index.php'; // sinon, on rtourne à l'accueil.
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
//ex : HomeController->users(null)  vérification des résultats de la page addTopic et du formulaire
//var_dump("Controller : $ctrlname");
//var_dump("Action : $action");
//var_dump("Id : ", $id);
//var_dump($_POST);
//die();
//------------
$result = $ctrl->$action($id);
if (!is_array($result)) {
    die("❌ La méthode $action() du contrôleur $ctrlNS ne retourne pas de tableau.");
}
$data = $result["data"] ?? [];

/*--------CHARGEMENT PAGE--------*/
if($action == "ajax"){ //si l'action était ajax
    //on affiche directement le return du contrôleur (càd la réponse HTTP sera uniquement celle-ci)
    echo json_encode($result);
}
else{
    ob_start();//démarre un buffer (tampon de sortie)
    $meta_description = $data["meta_description"] ?? "";

   
    include(VIEW_DIR . $result['view']); // <- attention ici !
    $page = ob_get_contents();
    ob_end_clean();
    include "view\layout.php";
}