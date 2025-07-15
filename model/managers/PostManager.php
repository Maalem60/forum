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

}
