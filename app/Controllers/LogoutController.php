<?php
namespace App\Controllers;

class LogoutController {
    /**
     * Process logout by destroying the session and redirecting to the login view.
     */
    public function processLogout() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
