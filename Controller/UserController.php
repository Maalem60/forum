<?php
namespace Controller;

use App\AbstractController;
use App\Session;

class UserController extends AbstractController
{

public function banUser()
{
    if (!Session::getUser()) {
        Session::addFlash("error", "Vous devez être connecté pour bannir un utilisateur.");
        $this->redirectTo("security", "login");
        return;
    }

    if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
        $userId = (int)$_GET['id'];
        $userManager = new \Model\Managers\UserManager();
        $userManager->banUser($userId);
        Session::addFlash("success", "Utilisateur banni avec succès.");
    }

    $this->redirectTo("forum", "listTopics");
}
}