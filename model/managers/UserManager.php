<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;
use Model\Entities\User;

class UserManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = User::class;
    protected $tableName = "user";

public function __construct(){
        parent::connect();
    }
    // ajouter un utilisateur
public function addUser($pseudo, $email, $passwordHash) {
    // Assurons-nous que la colonne dans la BDD s'appelle bien `pseudo` et pas `username`
    $sql = "INSERT INTO ".$this->tableName." (pseudo, email, password, creationDate) 
            VALUES (:pseudo, :email, :password, NOW())";  // Utilisation de `NOW()` pour l'ajout de la date de création
    
    return $this->add([
        'pseudo' => $pseudo,
        'email' => $email,
        'password' => $passwordHash
    ]);
}

// ...existing code...

public function findOneByEmail($email)
{
    $sql = "SELECT * FROM user WHERE email = :email";
    return $this->getOneOrNullResult(
        DAO::select($sql, ['email' => $email], false),
        $this->className
    );
}

// ...existing code...

// Exemple dans UserManager.php

public function findOneByPseudo($pseudo) {
    $sql = "SELECT * FROM user WHERE pseudo = :pseudo";
    $result = DAO::select($sql, ['pseudo' => $pseudo]);

    // Vérifie si un utilisateur a été trouvé et retourne un objet User
    return $result ? new User($result[0]) : null;
}

}