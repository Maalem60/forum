<?php

namespace App;
use Model\Managers\UserManager; 

class Session {

    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ---------------------------
    // GESTION DE L'UTILISATEUR
    // ---------------------------

    public static function isUserConnected(): bool {
         self::start();
       return isset($_SESSION['user_id']);
     }

    public static function setUser($user) {
       ;
        $_SESSION['user_id'] = $user->getId();
    }

    // public static function getUserId() {
    //     self::start();
    //     return $_SESSION['user_id'] ?? null;
    // }

    public static function getUser() {
   
        if (!isset($_SESSION['user_id'])) return null;

        $userManager = new UserManager();
        return $userManager->findOneById($_SESSION['user_id']);
    }

    public static function isAdmin() {
        $user = self::getUser();
        return $user && $user->hasRole("ROLE_ADMIN");
    }

    public static function destroy() {
        self::start();
        $_SESSION = [];
        session_destroy();
    }

    // ---------------------------
    // MESSAGES FLASH
    // ---------------------------

    public static function addFlash(string $type, string $msg) {
        self::start();
        $_SESSION['flash'][$type][] = $msg;
    }

    public static function getFlashes(string $type): array {
        self::start();
        if (!isset($_SESSION['flash'][$type])) {
            return [];
        }

        $messages = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);

        return $messages;
    }

    // Pour récupérer juste un seul message (le premier),
    public static function getFlash(string $type): ?string {
        $messages = self::getFlashes($type);
        return $messages[0] ?? null;
    }
}
