<?php
namespace App;

class Autoloader { // Carge automatiquement les classes

    public static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($class) {
        // Exemple : App\Collection ou Model\Managers\TopicManager

        // Transforme les antislashs (\) en séparateurs de dossier
        $parts = explode("\\", $class);

        // Récupère le nom de la classe (ex : TopicManager)
        $className = array_pop($parts);

        // Recompose le chemin (ex : App => app/, Model\Managers => model/managers/)
        $path = implode(DIRECTORY_SEPARATOR, $parts);

        // ⚠️ Si le dossier de base est en minuscule (comme app/), il ne faut pas forcer tout en minuscule
        // car "App" => "app", mais "Model" => "model"
        // Donc on peut appliquer une règle spéciale si besoin ici
        // -> ici on laisse les majuscules (meilleure compatibilité Windows/Linux)

        // Construit le chemin complet
        $file = $className . '.php';
        $filepath = BASE_DIR . $path . DIRECTORY_SEPARATOR . $file;

        // Charge le fichier s’il existe
        if (file_exists($filepath)) {
            require $filepath;
        }
    }
}
