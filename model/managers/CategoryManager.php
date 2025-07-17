<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class CategoryManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Category";
    protected $tableName = "category";

    public function __construct(){
        parent::connect();
    }

public function findAll($order = null) {
    $sql = "SELECT * FROM " . $this->tableName;
         // on vérifie que l'ordre demandé est valide (ex: nom de colonne et ASC/DESC)
         // on construit la clause ORDER BY dans la requête SQL
    if ($order && is_array($order) && count($order) === 2) {
         
        $allowedFields = ['id_category', 'name'];
        $allowedDirections = ['ASC', 'DESC'];
        if (in_array($order[0], $allowedFields) && in_array(strtoupper($order[1]), $allowedDirections)) {
            $sql .= " ORDER BY " . $order[0] . " " . strtoupper($order[1]);
        }
    }

    $results = $this->getMultipleResults(
        DAO::select($sql),
        $this->className
    );

    // On transforme le générateur en tableau et on crée la Collection
    return new \App\Collection(iterator_to_array($results));
}
    
}
