<?php
namespace App;

use Model\Entities\User;

abstract class Manager{
 
// Nom de la classe représentant l'entité à hydrater avec les données récupérées de la base.
// Elle sera utilisée lors de la conversion des résultats SQL en objets PHP.
protected $className = "Model\Entities\User";

// Nom de la table dans la base de données à interroger ou modifier.
// Ce nom est utilisé pour générer dynamiquement les requêtes SQL dans les méthodes génériques.
protected $tableName = "user";
protected $db; // Ajoutez cette ligne

public function __construct(){
    DAO::connect();  // initialise la connexion statique une fois
    $this->db = DAO::getBdd();  // récupère la connexion PDO pour usage interne
}

protected function connect(){
    $this->db = DAO::getBdd();  // récupère la connexion PDO et la stocke dans $this->db
}

    // * obtenir tous les enregistrements d'une table, triés par champ facultatif et par ordre
  //  * 
    // * @param array $order un tableau avec un champ et une option de commande
     //* @return Collection une collection d'objets hydratés par DAO, qui sont les résultats de la requête envoyée
     //*/
   
public function findAll($order = null){
    try {
        $allowedFields = ['id_user', 'pseudo', 'email', 'creationDate'];
        $allowedDirections = ['ASC', 'DESC'];

        $orderQuery = "";

        if ($order && in_array($order[0], $allowedFields) && in_array(strtoupper($order[1]), $allowedDirections)) {
            $orderQuery = "ORDER BY {$order[0]} " . strtoupper($order[1]);
        }

        $sql = "SELECT * FROM {$this->tableName} a $orderQuery";

        $generated = $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );

        return new \App\Collection(iterator_to_array($generated ?? []));
    }
    catch (\Exception $e) {
        //  logger ou afficher l’erreur selon le besoin
        echo "Erreur dans findAll() : ".$e->getMessage();
        return new \App\Collection(); // On retourne une collection vide pour ne pas planter
    }
}

    
public function findOneById($id){

        $sql = "SELECT *
                FROM ".$this->tableName." a
                WHERE a.id_".$this->tableName." = :id
                ";

        return $this->getOneOrNullResult(
            DAO::select($sql, ['id' => $id], false), 
            $this->className
        );
    }

    //$data = ['username' => 'Squalli', 'password' => 'dfsyfshfbzeifbqefbq', 'email' => 'sql@gmail.com'];
public function add($data){
    $keys = array_keys($data); // ['username', 'password', 'email']

    // On prépare : :username, :password, :email
    $placeholders = array_map(fn($key) => ":$key", $keys);

    $sql = "INSERT INTO ".$this->tableName."
            (".implode(',', $keys).")
            VALUES (".implode(',', $placeholders).")";

    try{
        $pdo = DAO::getBdd(); // ✅ accès correct via méthode publique
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return $pdo->lastInsertId(); // ✅ on utilise la même instance
    }
    catch(\PDOException $e){
        echo $e->getMessage();
        die();
    }
}


    
public function delete($id){
        $sql = "DELETE FROM ".$this->tableName."
                WHERE id_".$this->tableName." = :id
                ";

        return DAO::delete($sql, ['id' => $id]); 
    }

private function generate($rows, $class){
        foreach($rows as $row){
            yield new $class($row);
        }
    }
    
protected function getMultipleResults($rows, $className){

        if(is_iterable($rows)){
            return $this->generate($rows, $className);
        }
        else return null;
    }

protected function getOneOrNullResult($row, $class){

        if($row != null){
            return new $class($row);
        }
        return null;
    }

protected function getSingleScalarResult($row, $class):?User {
   if($row != null){
            $value = array_values($row);
            return $value[0];
        }
        return null;
    }

}
     


