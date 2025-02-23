<?php
namespace App\Controllers;

class LogoutController {
    /**
     * Process logout by clearing session data, deleting the session cookie, and redirecting to the login view.
     */
    public function processLogout() {
        // Start the session (if not already started)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Unset all of the session variables.
        $_SESSION = [];

        // Delete the session cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session.
        session_destroy();

        // Redirect to login
        header("Location: index.php?route=login");
        exit;
    }
}
