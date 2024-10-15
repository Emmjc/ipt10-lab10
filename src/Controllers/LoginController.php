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

    public function showForm()
    {
        session_start();
        // Disable the form if there are too many attempts
        $disabled = isset($_SESSION['attempts']) && $_SESSION['attempts'] >= 3;

        echo $this->mustache->render(file_get_contents(__DIR__ . '/../../views/login-form.mustache'), ['disabled' => $disabled]);
    }

    public function login()
    {
        session_start();

        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = 0;
        }

        if ($_SESSION['attempts'] >= 3) {
            return $this->showForm(); // Show form disabled
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
            return $this->showForm(); // Show form with error
        }
    }

    public function logout()
    {
        // Destroy the session
        session_start(); // Start the session if not already active
        session_unset(); // Remove all session variables
        session_destroy(); // Destroy the session
    
        // Redirect to the login page
        header("Location: /login-form");
        exit(); // Ensure no further code is executed after redirection
    }
    
}
