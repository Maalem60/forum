<?php
namespace App;
/**
 * Classe abstraite DAO (Data Access Object)
 * Fournit des méthodes statiques pour interagir avec la base de données
 * - Connexion
 * - Requêtes d'insertion, sélection, mise à jour et suppression
 *
 * @property-read \PDO $bdd Instance PDO stockée après connect()
 * @method static connect() Connexion à la BDD
 * @method static insert() Requêtes d'insertion
 * @method static select() Requêtes de sélection
 */
abstract class DAO
{
    // Paramètres de connexion à la base de données
    private static $host = 'localhost';
    private static $dbname = 'forum';
    private static $dbuser = 'root';
    private static $dbpass = '';

    // Instance unique de PDO
    private static $bdd;

    /**
     * Initialise la connexion PDO unique pour l'application
     */
    public static function connect()
    {
        self::$bdd = new \PDO(
            "mysql:host=" . self::$host . ";dbname=" . self::$dbname,
            self::$dbuser,
            self::$dbpass,
            [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4", // encodage UTF-8
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,        // exceptions sur erreurs SQL
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC    // fetch par défaut en tableau associatif
            ]
        );
    }

    /**
     * Retourne l'instance PDO
     */
    public static function getBdd()
    {
        return self::$bdd;
    }
    /**
     * Exécute une requête d'insertion
     * @param string $sql Requête SQL
     * @return string|null ID du dernier enregistrement inséré ou null en cas d'erreur
     */
    
     public static function insert($sql, $params = null)
     {
         try {
             $stmt = self::$bdd->prepare($sql);
             $stmt->execute($params);
             return self::$bdd->lastInsertId();
         } catch (\Exception $e) {
             error_log("Erreur SQL (insert) : " . $e->getMessage());
             echo "Une erreur est survenue. Veuillez réessayer plus tard.";
         }
     }
     
    /**
     * Exécute une requête de mise à jour
     * @param string $sql Requête SQL
     * @param array $params Paramètres liés à la requête
     * @return bool|null True si succès, false sinon, null si exception
     */
    public static function update($sql, $params)
    {
        try {
            $stmt = self::$bdd->prepare($sql);
            return $stmt->execute($params);
        } catch (\Exception $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    /**
     * Exécute une requête de suppression
     * @param string $sql Requête SQL
     * @param array $params Paramètres liés à la requête
     * @return bool|null True si succès, false sinon, null si exception
     */
    public static function delete($sql, $params)
    {
        try {
            $stmt = self::$bdd->prepare($sql);
            return $stmt->execute($params);
        } catch (\Exception $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    /**
     * Exécute une requête de type SELECT
     * @param string $sql Requête SQL
     * @param mixed $params=null Paramètres liés à la requête
     * @param bool $multiple=true True si plusieurs résultats, false pour un seul
     * @return array|null Résultat(s) de la requête ou null si aucun résultat
     */
    public static function select($sql, $params = null, bool $multiple = true): ?array
    {
        try {
            $stmt = self::$bdd->prepare($sql);
            $stmt->execute($params);

            $results = ($multiple) ? $stmt->fetchAll() : $stmt->fetch();
            $stmt->closeCursor();

            return ($results == false) ? null : $results;
        } catch (\Exception $e) {
            // Affichage détaillé pour débogage (arrête l'exécution)
            echo "<h2>Erreur SQL</h2>";
            echo "<pre>" . $e->getMessage() . "</pre>";
            echo "<hr>";
            echo "<strong>Requête :</strong><br><pre>$sql</pre>";
            echo "<strong>Paramètres :</strong><br><pre>" . print_r($params, true) . "</pre>";
            die();
        }
    }
}
