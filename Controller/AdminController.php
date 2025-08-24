<?php
namespace Controller;

use App\AbstractController;
use App\Session;
use Model\Managers\UserManager;

class AdminController extends AbstractController
{
    // Page d'accueil admin (dashboard)
    public function dashboard()
    {
        $this->restrictTo("ROLE_ADMIN");
        $this->render("admin/dashboard");
    }
// Changer le rôle d'un utilisateur
public function changeRole()
{
    $this->restrictTo("ROLE_ADMIN");

    if (isset($_GET['id'], $_GET['role']) && ctype_digit($_GET['id'])) {
        $userId = (int) $_GET['id'];
        $newRole = $_GET['role'];

        // Vérifier que le rôle demandé est valide
        $validRoles = ["ROLE_USER", "ROLE_MODERATOR", "ROLE_ADMIN"];
        if (!in_array($newRole, $validRoles)) {
            Session::addFlash("error", "Rôle invalide.");
            $this->redirectTo("admin", "listUsers");
            return;
        }

        $userManager = new UserManager();
        $targetUser = $userManager->findOneById($userId);

        if ($targetUser) {
            if ($targetUser->getRole() === "ROLE_ADMIN" && $newRole !== "ROLE_ADMIN") {
                Session::addFlash("error", "Impossible de changer le rôle d'un administrateur.");
            } else {
                $userManager->updateRole($userId, $newRole);
                Session::addFlash("success", "Rôle de l'utilisateur modifié avec succès.");
            }
        } else {
            Session::addFlash("error", "Utilisateur inexistant.");
        }
    } else {
        Session::addFlash("error", "Paramètres invalides.");
    }

    $this->redirectTo("admin", "listUsers");
}

    // Liste tous les utilisateurs
    public function listUsers()
    {
        $this->restrictTo("ROLE_ADMIN");

        $userManager = new UserManager();
        $users = $userManager->findAllUsers();   // utilise la méthode existante

        $this->render("admin/listUsers", ['users' => $users]);
    }

    // Bannir un utilisateur
    public function banUser()
    {
        $this->restrictTo("ROLE_ADMIN");

        if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
            $userId = (int)$_GET['id'];
            $userManager = new UserManager();

            $targetUser = $userManager->findOneById($userId);

            if ($targetUser) {
                if ($targetUser->getRole() !== "ROLE_ADMIN") {
                    $userManager->banUser($userId);  // met banned = 1
                    Session::addFlash("success", "Utilisateur banni avec succès.");
                } else {
                    Session::addFlash("error", "Impossible de bannir un administrateur.");
                }
            } else {
                Session::addFlash("error", "Utilisateur inexistant.");
            }
        } else {
            Session::addFlash("error", "ID utilisateur invalide.");
        }

        $this->redirectTo("admin", "listUsers");
    }
}
