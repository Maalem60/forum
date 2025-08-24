<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;
use Model\Entities\User;

class UserManager extends Manager
{
    protected $className = User::class;
    protected $tableName = "user";

    public function __construct() {
        parent::connect();
    }

    // Ajouter un utilisateur
    public function addUser($pseudo, $email, $passwordHash, $role) {
        return $this->add([
            'pseudo' => $pseudo,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role
        ]);
    }

    // Récupérer tous les utilisateurs
    public function findAllUsers() {
        $sql = "SELECT * FROM ".$this->tableName." ORDER BY id_user ASC";
        $results = DAO::select($sql);

        $users = [];
        if (!empty($results) && is_array($results)) {
            foreach ($results as $userData) {
                $users[] = new User($userData);
            }
        }
        return $users;
    }

    // Méthode générique pour trouver un utilisateur par un champ
    private function findOneByField($field, $value, $single = true) {
        $sql = "SELECT * FROM ".$this->tableName." WHERE $field = :value";
        $result = DAO::select($sql, ['value' => $value], $single);

        if (!empty($result) && is_array($result)) {
            return new User($result[0]);
        }
        return null;
    }

    // Récupérer un utilisateur par ID
    public function findOneById($id) {
        return $this->findOneByField('id_user', $id);
    }

    // Récupérer un utilisateur par email
    public function findOneByEmail($email) {
        return $this->findOneByField('email', $email);
    }

    // Récupérer un utilisateur par pseudo
    public function findOneByPseudo($pseudo) {
        return $this->findOneByField('pseudo', $pseudo);
    }

    // Changer le rôle d'un utilisateur
    public function updateRole($userId, $newRole) {
        $sql = "UPDATE ".$this->tableName." SET role = :role WHERE id_user = :id";
        return DAO::update($sql, [
            'role' => $newRole,
            'id'   => $userId
        ]);
    }

    // Bannir un utilisateur (banned = 1)
    public function banUser($userId) {
        $sql = "UPDATE ".$this->tableName." SET banned = 1 WHERE id_user = :id";
        return DAO::update($sql, ['id' => $userId]);
    }
}
