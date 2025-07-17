<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class PostManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Post";
    protected $tableName = "post";

public function __construct(){
        parent::connect();
    }

    // récupérer tous les topics d'une catégorie spécifique (par son id)
public function findPostsByTopic($idTopic)
{
    $sql = "SELECT * FROM post WHERE topic_id = :id ORDER BY creationDate ASC";

    return $this->getMultipleResults(
        DAO::select($sql, ['id' => $idTopic]),
        \Model\Entities\Post::class
    );
}
public function addPost($content, $user_id, $topic_id)
{
    $sql = "INSERT INTO post (content, user_id, topic_id, creationDate)
            VALUES (:content, :user_id, :topic_id, NOW())";

    // On passe un seul tableau avec la requête et les paramètres
    return DAO::insert([
        "sql" => $sql,
        "params" => [
            "content" => $content,
            "user_id" => $user_id,
            "topic_id" => $topic_id
        ]
    ]);
}

}
