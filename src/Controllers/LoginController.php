<?php

namespace App\Controllers;

use App\Models\User;
use Mustache_Engine;

class LoginController
{
    private $mustache;

    public function __construct()
    {
        $this->mustache = new Mustache_Engine();
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showForm()
    {
        $this->startSession();

        // Disable the form if there are too many attempts
        $disabled = false;
        if (isset($_SESSION['attempts']) && $_SESSION['attempts'] >= 3) {
            // Check if the lockout time has expired
            if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
                $disabled = true; // Still locked out
            } else {
                // Reset attempts and lockout time if lockout has expired
                $_SESSION['attempts'] = 0;
                unset($_SESSION['lockout_time']);
            }
        }

        // Pass error message if exists
        $errorMessage = $_SESSION['error_message'] ?? '';
        unset($_SESSION['error_message']); // Clear the error message after displaying

        echo $this->mustache->render(file_get_contents(__DIR__ . '/../../views/login-form.mustache'), [
            'disabled' => $disabled,
            'error_message' => $errorMessage // Add error message to the view
        ]);
    }

    public function login()
    {
        $this->startSession();

        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = 0;
        }

        // Check if the form is disabled
        if ($_SESSION['attempts'] >= 3) {
            // Check if the lockout time has expired
            if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
                return $this->showForm(); // Show form disabled
            } else {
                // Reset attempts and lockout time if lockout has expired
                $_SESSION['attempts'] = 0;
                unset($_SESSION['lockout_time']);
            }
        }

        $usernameOrEmail = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->login($usernameOrEmail, $password);

        if ($user) {
            // Successful login
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            header("Location: /welcome");
            exit();
        } else {
            // Failed login
            $_SESSION['attempts']++;
            $_SESSION['error_message'] = "Incorrect username or password."; // Set error message
            // Set lockout time if attempts reach 3
            if ($_SESSION['attempts'] >= 3) {
                $_SESSION['lockout_time'] = time() + 120; // Lockout for 2 minutes
            }
            return $this->showForm(); // Show form with error
        }
    }

    public function logout()
    {
        $this->startSession(); // Start the session if not already active
        session_unset(); // Remove all session variables
        session_destroy(); // Destroy the session

        // Redirect to the login page
        header("Location: /login-form");
        exit(); // Ensure no further code is executed after redirection
    }
}
