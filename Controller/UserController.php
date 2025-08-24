<?php
namespace Controller;

use App\AbstractController;
use App\Session;
use Model\Managers\UserManager; // ⬅️ ajout du use ici

class UserController extends AbstractController
{
public function listUsers()
{
    // Bloque l'accès si l'utilisateur n'est pas admin
    $this->restrictTo("ROLE_ADMIN");

    $userManager = new UserManager();
    $users = $userManager->findAll();

    $this->render('security/user', ['users' => $users]);
}

public function banUser()
{
    // Restreint l'accès à cette méthode aux utilisateurs ayant le rôle ROLE_ADMIN
    $this->restrictTo("ROLE_ADMIN");

    // Vérifie que l'identifiant de l'utilisateur à bannir est présent et valide (nombre entier)
    if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
        $userId = (int)$_GET['id'];
        $userManager = new UserManager();

        // Récupère les informations de l'utilisateur ciblé
        $targetUser = $userManager->findOneById($userId);

        // Vérifie que l'utilisateur ciblé existe et n'a pas le rôle ADMIN
        if ($targetUser && $targetUser->getRole() !== 'ROLE_ADMIN') {
            // Effectue le bannissement de l'utilisateur
            $userManager->banUser($userId);
            Session::addFlash("success", "Utilisateur banni avec succès.");
        } else {
            // Ne peut pas bannir un utilisateur inexistant ou un administrateur
            Session::addFlash("error", "Impossible de bannir cet utilisateur.");
        }
    } else {
        // L'identifiant passé est invalide ou absent
        Session::addFlash("error", "ID utilisateur invalide.");
    }

    // Redirige vers la liste des topics du forum
    $this->redirectTo("forum", "listTopics");
}

}