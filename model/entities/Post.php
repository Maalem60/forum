<?php
namespace Model\Entities;

use App\Entity;
use Model\Entities\User;
use Model\Entities\Topic;

/**
 * Classe Post
 * Représente un message/post d’un utilisateur dans un topic.
 * Hérite de Entity pour bénéficier des fonctionnalités communes (ex : hydrate).
 * La classe est final, donc elle ne peut pas être étendue.
 */
final class Post extends Entity {

    // Identifiant du post (clé primaire dans la BDD)
    private $id_post;

    // Contenu textuel du post
    private $content;

    // Date de création (stockée initialement comme chaîne de caractères depuis la BDD,
    // convertie en DateTime à l'utilisation)
    private $creationDate;

    // Utilisateur ayant créé le post (instance de User)
    private $user;

    // Topic auquel le post appartient (instance de Topic)
    private $topic;

    /**
     * Constructeur
     * Initialise l'objet avec les données fournies (hydratation)
     *
     * @param array $data Données initiales pour le post
     */
    public function __construct($data) {
        $this->hydrate($data);
    }

    // --- Getters et Setters ---

    /**
     * Retourne l'identifiant du post
     */
    public function getId() {
        return $this->id_post;
    }

    /**
     * Retourne le contenu du post
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Définit le contenu du post
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * Retourne la date de création sous forme d'objet DateTime
     * Convertit la chaîne de la BDD en DateTime si nécessaire
     */
    public function getCreationDate(): ?\DateTime {
        return $this->creationDate instanceof \DateTime
            ? $this->creationDate
            : ($this->creationDate ? new \DateTime($this->creationDate) : null);
    }

    /**
     * Définit la date de création
     * Accepte soit un objet DateTime soit une chaîne au format valide
     */
    public function setCreationDate(\DateTime|string $date): void {
        if ($date instanceof \DateTime) {
            $this->creationDate = $date;
        } else {
            $this->creationDate = new \DateTime($date);
        }
    }

    /**
     * Retourne l'utilisateur ayant créé le post
     */
    public function getUser(): ?User {
        return $this->user;
    }

    /**
     * Associe un utilisateur au post
     */
    public function setUser(\Model\Entities\User $user): void
    {
        $this->user = $user;
    }
    

    /**
     * Retourne le topic auquel appartient le post
     */
    public function getTopic(): ?Topic {
        return $this->topic;
    }

    /**
     * Associe un topic au post
     */
    public function setTopic(Topic $topic) {
        $this->topic = $topic;
    }

    /**
     * Méthode magique __toString
     * Permet d'utiliser l'objet Post comme une chaîne (renvoie le contenu)
     */
    public function __toString() {
        return $this->content;
    }
}
