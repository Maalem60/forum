<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;
use Model\Entities\User;

class UserManager extends Manager
{
<<<<<<< HEAD
    protected $className = User::class;
    protected $tableName = "user";

    public function __construct() {
        parent::connect();
    }

    // Ajouter un utilisateur
    public function addUser($pseudo, $email, $passwordHash, $role) {
=======

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = User::class;
    protected $tableName = "user";

    public function __construct()
    {
        parent::connect();
    }

    // ajouter un utilisateur
    public function addUser($pseudo, $email, $passwordHash, $role)
    {
        // Assurons-nous que la colonne dans la BDD s'appelle bien `pseudo` et pas `username`
        $sql = "INSERT INTO " . $this->tableName . " (pseudo, email, password, role) 
            VALUES (:pseudo, :email, :password, :role)";  // Utilisation de `NOW()` pour l'ajout de la date de création

>>>>>>> 7d21baa (Ajout des dernières modification + mises à jour des entités et vues + suppression d'anciennes images)
        return $this->add([
            'pseudo' => $pseudo,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role
        ]);
    }

<<<<<<< HEAD
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
=======
    // ...existing code...


    public function findOneByEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email = :email";
        return $this->getOneOrNullResult(
            DAO::select($sql, ['email' => $email], false),
            $this->className
        );
    }

    public function banUser($userId)
    {
        $sql = "UPDATE user SET banned = 1 WHERE id_user = :id";
        return DAO::update($sql, ["id_user" => $userId]);
    }
    // ...existing code...

    // Exemple dans UserManager.php

    public function findOneByPseudo($pseudo)
    {
        $sql = "SELECT * FROM user WHERE pseudo = :pseudo";
        $result = DAO::select($sql, ['pseudo' => $pseudo]);

        // Vérifie si un utilisateur a été trouvé et retourne un objet User
        return $result ? new User($result[0]) : null;
    }
    public function findAllUsers(): array
    {
        $sql = "SELECT * FROM " . $this->tableName . " ORDER BY id_user ASC";
        $results = DAO::select($sql);
    
        $users = [];
        foreach ($results as $userData) {
            // Si la colonne ROLE est en majuscule dans la BDD, on la normalise
            if (isset($userData['ROLE'])) {
                $userData['role'] = $userData['ROLE'];
                unset($userData['ROLE']);
            }
            $users[] = new User($userData);
        }
        return $users;
    }
    
    public function findOneById($id): ?User
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id_user = :id_user";
        $result = DAO::select($sql, ['id_user' => $id], false); // false = fetch one row
    
        if ($result) {
            if (isset($result['ROLE'])) { // normaliser aussi ici
                $result['role'] = $result['ROLE'];
                unset($result['ROLE']);
            }
            return new User($result);
        }
        return null;
    }
    
    public function updateRole($userId, $newRole)
    {
        $sql = "UPDATE " . $this->tableName . " SET role = :role WHERE id_user = :id_user";
        return DAO::update($sql, [
            'role' => $newRole,
            'id_user' => $userId
        ]);
    }
    
    


}
>>>>>>> 7d21baa (Ajout des dernières modification + mises à jour des entités et vues + suppression d'anciennes images)
