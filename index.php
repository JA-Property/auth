<?php
// auth_routes.php

// Include Composer's autoloader (handles PSR-4 autoloading)
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables from the .env file
use Dotenv\Dotenv;
try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    die("Dotenv load error: " . $e->getMessage());
}


// Must happen before session_start()
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path'     => '/',
    'domain'   => '.japropertysc.com', // leading dot
    'secure'   => $cookieParams['secure'],
    'httponly' => $cookieParams['httponly'],
    'samesite' => 'None'
]);

session_name('MYSESSIONID');
session_start();



// Simple routing using a query parameter (?route=)
// This file handles auth-specific routes only.
$route = $_GET['route'] ?? 'login';

switch ($route) {
    // Login Routes
    case 'login':
        // GET /login: Render login view; POST /login: Process login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\LoginController();
            $controller->processLogin();
        } else {
            $controller = new App\Controllers\LoginController();
            $controller->renderLoginView();

        }
        break;
    
    // Logout Route
    case 'logout':
        // GET /logout: Process logout
        $controller = new App\Controllers\LogoutController();
        $controller->processLogout();
        break;
    
    // STEP 1: Check the account
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\RegisterController();
            $controller->processAccountCheck();
        } else {
            $controller = new App\Controllers\RegisterController();
            $controller->renderAccountCheckView();
        }
        break;

    // STEP 2: Create user
    case 'register-create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\RegisterController();
            $controller->processUserCreation();
        } else {
            $controller = new App\Controllers\RegisterController();
            $controller->renderUserCreationView();
        }
        break;
    // Account Activation Route
    case 'activate':
        // GET /activate?token=XYZ: Process account activation via token
        $controller = new App\Controllers\ActivateController();
        $controller->processActivation();
        break;
    
    // Forgot Password Routes
    case 'forgot-password':
        // GET /forgot-password: Render forgot password view; POST /forgot-password: Process request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\ForgotPasswordController();
            $controller->processForgotPassword();
        } else {
            $controller = new App\Controllers\ForgotPasswordController();
            $controller->renderForgotPasswordView();
        }
        break;
    
    // Reset Password Routes
    case 'reset-password':
        // GET /reset-password?token=XYZ: Render reset password view; POST /reset-password: Process reset
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new App\Controllers\ResetPasswordController();
            $controller->processResetPassword();
        } else {
            $controller = new App\Controllers\ResetPasswordController();
            $controller->renderResetPasswordView();
        }
        break;
    
    // Fallback for undefined routes
    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        break;
}
