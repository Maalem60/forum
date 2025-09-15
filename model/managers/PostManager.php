<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;
use Model\Entities\Post;
use Model\Entities\User;
use Model\Entities\Topic;

class PostManager extends Manager
{
    protected $className = Post::class;
    protected $tableName = "post";

    public function __construct()
    {
        parent::connect();
    }

    /**
     * Récupère tous les posts d'un topic avec les objets User et Topic associés
     */
    public function findPostsByTopic($idTopic)
    {
        $sql = "SELECT 
                    p.*, 
                    u.id_user AS user_id, u.pseudo AS user_pseudo, u.email AS user_email, u.role AS user_role,
                    t.id_topic AS topic_id, t.title AS topic_title
                FROM post p
                LEFT JOIN user u ON p.user_id = u.id_user
                LEFT JOIN topic t ON p.topic_id = t.id_topic
                WHERE p.topic_id = :id_topic
                ORDER BY p.creationDate ASC";

        $results = DAO::select($sql, ['id_topic' => $idTopic]);

        $posts = [];
        foreach ($results as $row) {
            // Crée l'objet Post
            $post = new Post([
                'id_post' => $row['id_post'],
                'content' => $row['content'],
                'creationDate' => $row['creationDate']
            ]);

            // Hydrate l'utilisateur si existant
            if (!empty($row['user_id'])) {
                $post->setUser(new User([
                    'id_user' => $row['user_id'],
                    'pseudo' => $row['user_pseudo'],
                    'email' => $row['user_email'],
                    'ROLE' => $row['user_role']
                ]));
            }

            // Hydrate le topic si existant
            if (!empty($row['topic_id'])) {
                $post->setTopic(new Topic([
                    'id_topic' => $row['topic_id'],
                    'title' => $row['topic_title']
                ]));
            }

            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * Ajouter un post
     */
    public function addPost($content, $user_id, $topic_id)
    {
        $sql = "INSERT INTO post (content, user_id, topic_id, creationDate)
                VALUES (:content, :user_id, :topic_id, NOW())";

        return DAO::insert($sql, [
            "content" => $content,
            "user_id" => $user_id,
            "topic_id" => $topic_id
        ]);
    }

    /**
     * Supprimer un post par son id
     */
    public function deletePost($id_post)
    {
        $sql = "DELETE FROM post WHERE id_post = :id_post";
        return DAO::delete($sql, ['id_post' => $id_post]);
    }
}
