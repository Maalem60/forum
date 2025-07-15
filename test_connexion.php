<?php
try {
    $host = 'localhost';
    $port = 3306;
    $dbname = 'forum'; // ou 'forummvc_v2' selon ta config
    $user = 'root';
    $pass = '';

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "✅ Connexion à la base réussie !";

} catch (PDOException $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
