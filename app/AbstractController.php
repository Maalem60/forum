<?php
namespace App;

/*
    En programmation orientée objet, une classe abstraite est une classe qui ne peut pas être instanciée directement. Cela signifie que vous ne pouvez pas créer un objet directement à partir d'une classe abstraite.
    Les classes abstraites : 
    -- peuvent contenir à la fois des méthodes abstraites (méthodes sans implémentation) et des méthodes concrètes (méthodes avec implémentation).
    -- peuvent avoir des propriétés (variables) avec des valeurs par défaut.
    -- une classe peut étendre une seule classe abstraite.
    -- permettent de fournir une certaine implémentation de base.
*/

abstract class AbstractController{

public function index() {}

public function redirectTo($ctrl = null, $action = null, $id = null){

        $url = $ctrl ? "?ctrl=".$ctrl : "";
        $url.= $action ? "&action=".$action : "";
        $url.= $id ? "&id=".$id : "";

        header("Location: $url");
        die();
    }

    protected function restrictTo($role): void
    {
        $user = Session::getUser();
    
        if (!$user || !method_exists($user, 'hasRole') || !$user->hasRole($role)) {
            $this->redirectTo("security", "login");
            exit;
        }
    }
    

 //  AJOUT DE LA MÉTHODE "render", permet de ne pas avoir à répéter cette structure à chaque fois. ICI 👉
public function render(string $view, array $data = [], string $meta = "")
{
    // Rend les variables du tableau $data disponibles directement dans la vue
    extract($data);

    // Capture le contenu généré par la vue
    ob_start();
    require VIEW_DIR . $view . ".php";
    $page = ob_get_clean();

    // Inclut le layout principal, qui contient le header, le footer, etc.
    require VIEW_DIR . "layout.php";
}

}