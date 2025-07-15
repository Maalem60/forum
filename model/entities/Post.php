<?php
namespace Model\Entities;

use App\Entity;

final class Post extends Entity {

    private $id;
    private $content;
    private $creationDate;
    private $user;     // instance de User
    private $topic;    // instance de Topic

public function __construct($data) {
    $this->hydrate($data);
}


    public function getId() {
        return $this->id;
    }
   
    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationDate($date) {
        $this->creationDate = $date;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getTopic() {
        return $this->topic;
    }

    public function setTopic($topic) {
        $this->topic = $topic;
    }

    public function __toString() {
        return $this->content;
    }
}
