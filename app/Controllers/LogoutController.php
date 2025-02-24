<?php
namespace App\Controllers;

class LogoutController {
    /**
     * Process logout by clearing session data, deleting the session cookie, and redirecting to the login view.
     */
    public function processLogout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Debugging: Print session data before clearing
    var_dump($_SESSION);

    // Unset all of the session variables.
    $_SESSION = [];

    // Debugging: Print session data after clearing
    var_dump($_SESSION);

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

    // Prevent caching of the login page
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.

    // Redirect to login
    header("Location: /");
    exit;
}
}