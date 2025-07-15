<?php
namespace Controller;

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
            "view" => VIEW_DIR."forum/listCategories.php",
            "meta_description" => "Liste des catégories du forum",
            "data" => [
                "categories" => $categories
            ]
        ];
    }
// public function listTopics($id) {
//     return $this->listTopicsByCategory($id);
// }
public function addTopic()
    {
        // Vérifier si utilisateur connecté
        if (!Session::isUserConnected()) {
            // Redirection vers login
            header("Location: index.php?ctrl=security&action=login");
            exit();
        }

        // Sinon, afficher la vue formulaire d'ajout de topic
        $this->render('forum/addTopic');
    }
public function saveTopic()
{
    // 1. Vérifier la connexion utilisateur
    if (!Session::isUserConnected()) {
        $this->redirectTo("security", "login");
    }

    // 2. Vérifier que les champs attendus existent dans $_POST
    if (isset($_POST['title'], $_POST['category_id'], $_POST['content'])) {
        
        // Nettoyage des données (trim pour éviter les espaces inutiles)
        $title = trim($_POST['title']);
        $category_id = (int) $_POST['category_id'];
        $content = trim($_POST['content']);
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
            "view" => VIEW_DIR."forum/listTopics.php",
            "meta_description" => "Liste des topics par catégorie : ".$category,
            "data" => [
                "category" => $category,
                "topics" => $topics
            ]
        ];
    }

public function listPostsByTopic($id) {

        $postManager = new PostManager();
        $topicManager = new TopicManager();
        $topic = $topicManager->findOneById($id);
        $posts = $postManager->findPostsByTopic($id);

      
        return [
            "view" => VIEW_DIR."forum/listPost.php",
            "meta_description" => "Liste des posts par topics : ".$topic,
            "data" => [
                "topic" => $topic,
                "posts" => $posts
            ]
        ];
    }
}