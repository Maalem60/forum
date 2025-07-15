<?php
namespace App;

class Session {

   private static $categories = ['error', 'success'];

public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

public static function isUserConnected(): bool {
        self::start();
        return isset($_SESSION['user_id']);  
    }

    /**
    *   ajoute un message en session, dans la catégorie $categ
    */
public static function addFlash($categ, $msg) {
        $_SESSION[$categ] = $msg;
    }

    /**
    *   renvoie un message de la catégorie $categ, s'il y en a !
    */
public static function getFlash($categ) {
        if (isset($_SESSION[$categ])) {
            $msg = $_SESSION[$categ];  
            unset($_SESSION[$categ]);
        return $msg; 
        } 
        return "";
    }

    /**
    *   met un user dans la session (pour le maintenir connecté)
    */
public static function setUser($user) {
        self::start();
        $_SESSION['user_id'] = $user->getId();
    }
public static function getUser() {
    self::start();
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $userManager = new \Model\Managers\UserManager();
    return $userManager->findOneById($_SESSION['user_id']);
}
public static function getUserId() {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }

 public static function isAdmin() {
        $user = self::getUser();
        if ($user && $user->hasRole("ROLE_ADMIN")) {
            return true;
        }
        return false;
    }

    // Ajouter cette méthode destroy() pour détruire la session
public static function destroy() {
        // Démarre la session si ce n'est pas déjà fait
        self::start();

        // Supprime toutes les variables de session
        $_SESSION = [];

        // Détruire la session
        session_destroy();
    }
}
