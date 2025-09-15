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

        // 1️ Vérification du token CSRF
        $token_post = $_POST['csrf_token'] ?? '';
        $token_session = $_SESSION['csrf_token'] ?? '';
        if (!hash_equals($token_session, $token_post)) {
            Session::addFlash("error", "Jeton CSRF invalide.");
            $this->redirectTo("security", "login");
            return;
        }
        unset($_SESSION['csrf_token']); // régénération

        // 2️ Récupération des données
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = $_POST['password']; // brut pour password_verify

        if (!$pseudo || !$password) {
            Session::addFlash("error", "❌ Veuillez remplir tous les champs.");
            $this->redirectTo("security", "login");
            return;
        }

        // 3️ Recherche utilisateur
        $userManager = new UserManager();
        $user = $userManager->findOneByPseudo($pseudo);

        // 4️ Vérification du mot de passe
        if ($user && password_verify($password, $user->getPassword())) {
            Session::setUser($user);
            Session::addFlash("success", "Connexion réussie !");
            $this->redirectTo("forum", "index");
        } else {
            Session::addFlash("error", "❌ Pseudo ou mot de passe incorrect");
            $this->redirectTo("security", "login");
        }
    } else {
        // 5️ Génération du token CSRF pour le formulaire
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $token = $_SESSION['csrf_token'];

        return [
            "view" => "security/login.php",
            "meta_description" => "Connexion à l'espace membre",
            "data" => ["token" => $token]
        ];
    }
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
