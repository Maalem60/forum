<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use App\Session;

class SecurityController extends AbstractController implements ControllerInterface{
    // Méthodes liées à l'authentification : register, login, logout

public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);  // Remplacer 'username' par 'pseudo'
            $password = $_POST['password']; 

            $userManager = new \Model\Managers\UserManager();
            $user = $userManager->findOneByPseudo($pseudo);

            if ($user && password_verify($password, $user->getPassword())) {
                session_start();
              $_SESSION['user_id'] = $user->getId();  // Stocke seulement l'ID de l'utilisateur  
              header("location: index.php?ctrl=security&action=profile");
                $this->redirectTo("forum", "index");  // Redirection vers le forum
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

public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];

            if ($pseudo && $email && $password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
                $userManager = new \Model\Managers\UserManager();
                $userManager->addUser($pseudo, $email, $hashedPassword);

                $message = "✅ Utilisateur $pseudo inscrit avec succès !";
            } else {
                $message = "❌ Données invalides, inscription échouée.";
            }

            return [
                "view" => VIEW_DIR . "security/register.php",
                "meta_description" => "Inscription sur le forum",
                "data" => [ "message" => $message ]
            ];
        }

        return [
            "view" => VIEW_DIR . "security/register.php",
            "meta_description" => "Inscription sur le forum"
        ];
    }

public function logout() {
        Session::destroy();  // Méthode pour détruire la session
        $this->redirectTo("security", "login");  // Redirige vers la page de login après déconnexion
    }

    // La méthode `profile()` qui affiche les informations de l'utilisateur connecté
public function profile() {
        // Vérifier si l'utilisateur est connecté
        if (!Session::isUserConnected()) {
            // Si l'utilisateur n'est pas connecté, on le redirige vers la page de login
            $this->redirectTo("security", "login");
        }

        // Récupérer l'utilisateur depuis la session
        $user = Session::getUser(); // Récupère l'utilisateur connecté depuis la session
       // et on passe cet objet à la vue.
    if (!$user) {
        die("Erreur : utilisateur non trouvé.");
    }
    
        // Retourner la vue du profil avec les données de l'utilisateur
        return [
            "view" => VIEW_DIR . "security/profile.php",  // La vue pour afficher le profil
            "meta_description" => "Profil de l'utilisateur",
            "data" => [
                "user" => $user  // Passer l'utilisateur dans les données de la vue
            ]
        ];
    }

    // La méthode `index()` qui pourrait rediriger vers le profil ou la page de login
public function index() {
        // Si l'utilisateur est connecté, rediriger vers le profil
        if (Session::isUserConnected()) {
            return $this->profile();  // Appel de la méthode profile()
        }

        // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
        $this->redirectTo("security", "login");
    }
public function listUsers() {
    $userManager = new \Model\Managers\UserManager();
    $users = $userManager->findAll();

    return [
        "view" => VIEW_DIR . "security/users.php",
        "meta_description" => "Liste des utilisateurs inscrits",
        "data" => [
            "users" => $users
        ]
    ];
}
public function showUser() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userManager = new \Model\Managers\UserManager();
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
