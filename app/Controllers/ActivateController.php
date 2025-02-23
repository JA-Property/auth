<?php
namespace App\Controllers;

class ActivateController {
    /**
     * Process account activation using a token passed in the query string.
     */
    public function processActivation() {
        if (!isset($_GET['token'])) {
            echo "Activation token missing.";
            exit;
        }
        $token = $_GET['token'];

        // Activation logic here (e.g., verify token, update user status in the database)
        // For demonstration, assume activation is successful:
        echo "Account successfully activated. You may now <a href='index.php?route=login'>login</a>.";
        exit;
    }
}
