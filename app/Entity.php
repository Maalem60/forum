<?php
namespace App;

/**
 * Classe abstraite Entity
 * Sert de classe "mère" à toutes les entités (User, Topic, Post...).
 * Elle permet notamment l'hydratation automatique à partir d'un tableau de données.
 */
abstract class Entity{

    /**
     * Méthode d'hydratation : prend un tableau de données (souvent issu de la BDD)
     * et appelle automatiquement les bons setters de l'entité.
     * 
     * @param array $data Données à injecter dans l'entité (ex: ["topic_id" => 1, "title" => "Mon sujet"])
     */
    
     public function hydrate($data)
     {
         foreach ($data as $field => $value) {
             $method = 'set' . ucfirst($field);
     
             if (method_exists($this, $method)) {
                 // Cas normal (champ simple : title, content, etc.)
                 $this->$method($value);
                 continue;
             }
     
             // Cas particulier : clé étrangère
             $entityName = null;
     
             // cas "user_id", "topic_id" ...
             if (preg_match('/(.+)_id$/', $field, $matches)) {
                 $entityName = ucfirst($matches[1]);
             }
             // cas "id_user", "id_topic" ...
             elseif (preg_match('/^id_(.+)$/', $field, $matches)) {
                 $entityName = ucfirst($matches[1]);
             }
     
             if ($entityName) {
                 $managerClass = "Model\\Managers\\" . $entityName . "Manager";
                 $setter = 'set' . $entityName;
     
                 if (class_exists($managerClass) && method_exists($this, $setter)) {
                     $manager = new $managerClass();
                     $relatedEntity = $manager->findOneById($value);
     
                     if ($relatedEntity) {
                         $this->$setter($relatedEntity); // passe un objet au setter
                     }
                 }
             }
         }
     }
     
    /**
     * Retourne le nom complet de la classe (namespace inclus)
     * Utile pour du debug ou de la réflexion.
     */
    public function getClass(){
        return get_class($this);
    }
}
