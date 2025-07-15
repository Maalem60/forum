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

            $userManager = new UserManager();
            $user = $userManager->findOneByPseudo($pseudo);

            if ($user && password_verify($password, $user->getPassword())) {
                session_start();
                $_SESSION['user_id'] = $user->getId();
                $this->redirectTo("forum", "index");
            } else {
                $message = "❌ Identifiants incorrects";
            }
        }

        return [
            "view" => VIEW_DIR . "security/login.php",
            "meta_description" => "Connexion à l'espace membre",
            "data" => ["message" => $message ?? ""]
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
                    $message = "❌ Les mots de passe ne correspondent pas.";
                } elseif (strlen($password) < 6) {
                    $message = "❌ Le mot de passe doit contenir au moins 6 caractères.";
                } else {
                    $userManager = new UserManager();

                    $pseudoExists = $userManager->findOneByPseudo($pseudo);
                    $emailExists = $userManager->findOneByEmail($email);

                    if ($pseudoExists || $emailExists) {
                        $message = "❌ Ce pseudo ou email est déjà utilisé.";
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $userManager->addUser($pseudo, $email, $hashedPassword);

                        $message = "✅ Utilisateur $pseudo inscrit avec succès !";
                    }
                }
            } else {
                $message = "❌ Veuillez remplir tous les champs.";
            }

            return [
                "view" => VIEW_DIR . "security/register.php",
                "meta_description" => "Inscription sur le forum",
                "data" => ["message" => $message]
            ];
        }

        return [
            "view" => VIEW_DIR . "security/register.php",
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
            "view" => VIEW_DIR . "security/profile.php",
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
            "view" => VIEW_DIR . "security/users.php",
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
            "view" => VIEW_DIR . "security/profile.php",
            "meta_description" => "Profil d’un utilisateur",
            "data" => [
                "user" => $user
            ]
        ];
    }
}
