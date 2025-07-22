<?php
// TopicManager.php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class TopicManager extends Manager {

    protected $className = "Model\Entities\Topic";
    protected $tableName = "topic";

    public function __construct() {
        parent::__construct();  // Obligatoire pour initialiser $this->db
    }


function findAllTopics() {
    $sql = "SELECT t.id_topic, t.title, t.creationDate, t.user_id, t.category_id,
                   u.pseudo, u.email
            FROM topic t
            INNER JOIN user u ON t.user_id = u.id_user
            ORDER BY t.creationDate DESC";
    // ...suite du code...



        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $topics = [];

        foreach ($rows as $row) {
            $topic = new \Model\Entities\Topic([
                'id_topic' => $row['id_topic'],
                'title' => $row['title'],
                'creationDate' => $row['creationDate'],
                'user_id' => $row['user_id'],
                'category_id' => $row['category_id']
            ]);

            $user = new \Model\Entities\User([
                'id' => $row['user_id'],
                'pseudo' => $row['pseudo'],
                'email' => $row['email']
            ]);

            $topic->setUser($user);

            $topics[] = $topic;
        }

        return $topics;
    }


    // récupérer tous les topics d'une catégorie spécifique (par son id)

public function findTopicsByCategory($id) {

    $sql = "SELECT t.id_topic, t.title, t.creationDate, t.user_id, t.category_id,
                   u.pseudo, u.email
            FROM topic t
            INNER JOIN user u ON t.user_id = u.id_user
            WHERE t.category_id = :id
            ORDER BY t.creationDate DESC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $topics = [];

    foreach ($rows as $row) {
        $topic = new \Model\Entities\Topic([
            'id_topic' => $row['id_topic'],
            'title' => $row['title'],
            'creationDate' => $row['creationDate'],
            'user_id' => $row['user_id'],
            'category_id' => $row['category_id']
        ]);

        $user = new \Model\Entities\User([
            'id' => $row['user_id'],
            'pseudo' => $row['pseudo'],
            'email' => $row['email']
        ]);

        $topic->setUser($user);

        $topics[] = $topic;
    }

    return $topics;
}
}