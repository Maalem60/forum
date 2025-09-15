<?php
namespace controller;

use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;
use Model\Managers\PostManager;

class ForumController extends AbstractController implements ControllerInterface{

public function index() {
        
        // créer une nouvelle instance de CategoryManager
        $categoryManager = new CategoryManager();
        // récupérer la liste de toutes les catégories grâce à la méthode findAll de Manager.php (triés par nom)
        $categories = $categoryManager->findAll(["name", "DESC"]);

        // le controller communique avec la vue "listCategories" (view) pour lui envoyer la liste des catégories (data)
        return [
            "view" => "forum/listCategories.php",
            "meta_description" => "Liste des catégories du forum",
            "data" => [
                "categories" => $categories
            ]
        ];
    }
   
public function addPost() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && Session::getUser()) {
           // Vérification CSRF
           if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            Session::addFlash("error", "Jeton CSRF invalide. Rechargement de la page nécessaire.");
            $this->redirectTo("forum", "listPostsByTopic", $_GET['id'] ?? null);
            return;
        }

        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id_topic = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id_user = Session::getUser()->getId();

        if ($content && $id_topic) {
            $postManager = new PostManager();
            $postManager->addPost($content, $id_user, $id_topic);
  Session::addFlash("success", "Votre message a été posté avec succès.");
           // Redirection vers le topic
            $this->redirectTo("forum", "listPostsByTopic", $id_topic);
        } else {
            Session::addFlash("error", "Le message est vide ou invalide.");
            $this->redirectTo("forum", "listPostsByTopic", $id_topic);
        }
        }
    }


// public function listTopics($id) {
//     return $this->listTopicsByCategory($id);

public function addTopic()
    {
       // var_dump($_POST);
       // exit;

        // Vérifier si utilisateur connecté
        if (!Session::isUserConnected()) {
            // Redirection vers login
            header("Location: index.php?ctrl=security&action=login");
            exit();
        }
         // Instancie le manager des catégories
         $categoryManager = new CategoryManager();
         // Récupère toutes les catégories, triées par nom croissant
         $categories = $categoryManager->findAll();  // doit retourner une App\Collection
    
         return [
        "view" => "forum/addTopic.php",
        "data" => ["categories" => $categories]
    ];

        // Sinon, afficher la vue formulaire d'ajout de topic
       // $this->render('forum/addTopic');
    }
    public function saveTopic()
    {
        // 1️ Vérifier la connexion utilisateur
        if (!Session::isUserConnected()) {
            $this->redirectTo("security", "login");
        }
    
        // 2️ Vérifier le POST et le token CSRF
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token_post = $_POST['csrf_token'] ?? '';
            $token_session = $_SESSION['csrf_token'] ?? '';
    
            if (!hash_equals($token_session, $token_post)) {
                Session::addFlash("error", "Jeton CSRF invalide. Rechargement nécessaire.");
                $this->redirectTo("forum", "addTopic");
                return;
            }
    
            // Optionnel : régénérer le token pour éviter la réutilisation
            unset($_SESSION['csrf_token']);
    
            // 3 Récupération et sanitation des données
            $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $category_id = filter_input(INPUT_POST, "category_id", FILTER_VALIDATE_INT);
            $content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            if (!$title || !$category_id || !$content) {
                Session::addFlash("error", "Tous les champs sont obligatoires.");
                $this->redirectTo("forum", "addTopic");
                return;
            }
    
            // 4️ Création du topic via TopicManager
            $topicManager = new TopicManager();
            $postManager = new PostManager();
    
            $topic_id = $topicManager->add([
                "title" => $title,
                "category_id" => $category_id,
                "user_id" => Session::getUser()->getId()
            ]);
    
            // 5️ Création du premier post via PostManager
            $postManager->add([
                "content" => $content,
                "topic_id" => $topic_id,
                "user_id" => Session::getUser()->getId()
            ]);
    
            // 6️ Message de succès et redirection
            Session::addFlash("success", "Topic créé avec succès !");
            $this->redirectTo("forum", "listPostsByTopic", $topic_id);
        } else {
            Session::addFlash("error", "Méthode invalide.");
            $this->redirectTo("forum", "addTopic");
        }
    }
    
    
public function listTopicsByCategory($id) {

        $topicManager = new TopicManager();
        $categoryManager = new CategoryManager();
        $category = $categoryManager->findOneById($id);
        $topics = $topicManager->findTopicsByCategory($id);

        return [
            "view" => "forum/listTopics.php",
            "meta_description" => "Liste des topics par catégorie : ".$category,
            "data" => [
                "category" => $category,
                "topics" => $topics
            ]
        ];
    }
public function listAllTopics() {
    $topicManager = new TopicManager();
    $topics = $topicManager->findAllTopics();
   
    return [
        "view" => "forum/listTopics.php",
        "data" => [
            "topics" => $topics
        ]
    ];
}

public function listPostsByTopic($id) {

        $postManager = new PostManager();
        $topicManager = new TopicManager();
        $topic = $topicManager->findOneById($id);
        $posts = $postManager->findPostsByTopic($id);

        $showForm = Session::isUserConnected();
        return [
            "view" => "forum/listPost.php",
            "meta_description" => "Liste des posts par topics : ".$topic,
            "data" => [
                "topic" => $topic,
                "posts" => $posts,
                "showForm" => $showForm 
            ]
        ];
    }
}