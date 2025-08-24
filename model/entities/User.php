<?php
namespace Model\Entities;

use App\Entity;

class User extends Entity
{

    private $id;
    private $pseudo;
    private $email;
    private $password;
    private $creationDate;
    private $avatar;
    private $role;  // Ajout de la propriété des rôles

    public function __construct($data)
    {
        // $this->role = []; // initialiser les roles de chacun ici.       
        $this->hydrate($data);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    // Méthode pour vérifier si l'utilisateur a un rôle spécifique
    // Gestion des rôles
    public function getRole()
    {
        return $this->role;
    }
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    public function hasRole($role)
    { // Vérifier si le rôle existe dans le tableau des rôles
        $this->role === $role;
    }


    public function __toString(): string
    {
        return $this->pseudo ?? "utilisateur inconnu";
    }
}
