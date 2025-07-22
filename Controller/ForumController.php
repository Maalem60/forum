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
      //  $this->render('forum/addTopic');
    }
public function saveTopic()
{
    var_dump($_POST); // pour vérifier que les données arrivent bien
    die(); // pour stopper ici et voir le résultat
    // 1. Vérifier la connexion utilisateur
    if (!Session::isUserConnected()) {
        $this->redirectTo("security", "login");
    }
    // 2. Vérifier que les champs attendus existent dans $_POST
    if (isset($_POST['title'], $_POST['category_id'], $_POST['content'])) {   
        // Nettoyage des données (trim pour éviter les espaces inutiles)
        $title = filter_input($_POST['title']);
        $category_id = (int) $_POST['category_id'];
        $content = filter_input($_POST['content']);
        $user_id = Session::getUserId();
        // Sécurité basique : ne pas continuer si un champ est vide
        if ($title && $category_id && $content) {
            $topicManager = new TopicManager();
            $postManager = new PostManager();
            // 3. Créer le topic
            $topicId = $topicManager->add([
                "title" => $title,
                "user_id" => $user_id,
                "category_id" => $category_id,
                "creationDate" => date("Y-m-d H:i:s")
            ]);
            // 4. Créer le premier post
            $postManager->add([
                "content" => $content,
                "user_id" => $user_id,
                "topic_id" => $topicId,
                "creationDate" => date("Y-m-d H:i:s")
            ]);
            // 5. Redirection vers le topic
            $this->redirectTo("forum", "listPostsByTopic", $topicId);
        }
        else {
            Session::addFlash("error", "Tous les champs sont obligatoires.");
            $this->redirectTo("forum", "addTopic");
        }
    } else {
        Session::addFlash("error", "Formulaire incomplet.");
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