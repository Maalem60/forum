<?php

namespace controller;

use App\AbstractController;
use App\ControllerInterface;
use App\Session;
use Model\Managers\UserManager;

class SecurityController extends AbstractController implements ControllerInterface
{
    // Méthodes liées à l'authentification : register, login, logout

   public function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$pseudo || !$password) {
            Session::addFlash("error", "❌ Veuillez remplir tous les champs.");
            $this->redirectTo("security", "login");
            return;
        }

        $userManager = new UserManager();
        $user = $userManager->findOneByPseudo($pseudo);

        if ($user && password_verify($password, $user->getPassword())) {
            session_start();
            $_SESSION['user_id'] = $user->getId();
            $this->redirectTo("forum", "index");
        } else {
            Session::addFlash("error", "❌ Identifiants incorrects");
            $this->redirectTo("security", "login");
        }
    }

    return [
        "view" => "security/login.php",
        "meta_description" => "Connexion à l'espace membre"
    ];
}


    public function register()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if ($pseudo && $email && $password && $password2) {
            if ($password !== $password2) {
                Session::addFlash("error", "❌ Les mots de passe ne correspondent pas.");
            } elseif (strlen($password) < 6) {
                Session::addFlash("error", "❌ Le mot de passe doit contenir au moins 6 caractères.");
            } else {
                $userManager = new UserManager();

                $pseudoExists = $userManager->findOneByPseudo($pseudo);
                $emailExists = $userManager->findOneByEmail($email);

                $role = 'ROLE_USER';

                if ($pseudoExists || $emailExists) {
                    Session::addFlash("error", "❌ Ce pseudo ou email est déjà utilisé.");
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $userManager->addUser($pseudo, $email, $hashedPassword, $role);

                    Session::addFlash("success", "✅ Utilisateur $pseudo inscrit avec succès !");
                    $this->redirectTo("security", "login");
                }
            }
        } else {
            Session::addFlash("error", "❌ Veuillez remplir tous les champs.");
        }

        // En cas d'erreur, on revient sur la page d'inscription
        $this->redirectTo("security", "register");
    }

    // Si ce n’est pas une requête POST, on affiche le formulaire
    return [
        "view" => "security/register.php",
        "meta_description" => "Inscription sur le forum"
    ];
}

    public function logout()
    {
        Session::destroy();
        $this->redirectTo("security", "login");
    }

    public function profile()
    {
        if (!Session::isUserConnected()) {
            $this->redirectTo("security", "login");
        }

        $user = Session::getUser();

        if (!$user) {
            die("Erreur : utilisateur non trouvé.");
        }

        return [
            "view" => "security/profile.php",
            "meta_description" => "Profil de l'utilisateur",
            "data" => [
                "user" => $user
            ]
        ];
    }

    public function index()
    {
        if (Session::isUserConnected()) {
            return $this->profile();
        }

        $this->redirectTo("security", "login");
    }

    public function listUsers()
    {
        $userManager = new UserManager();
        $users = $userManager->findAll();

        return [
            "view" => "security/users.php",
            "meta_description" => "Liste des utilisateurs inscrits",
            "data" => [
                "users" => $users
            ]
        ];
    }

    public function showUser()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $userManager = new UserManager();
        $user = $userManager->findOneById($id);

        return [
            "view" => "security/profile.php",
            "meta_description" => "Profil d’un utilisateur",
            "data" => [
                "user" => $user
            ]
        ];
    }
}
