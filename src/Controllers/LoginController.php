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

    // Display the login form
    public function showForm(): void
    {
        // Render the Mustache view for the login form
        echo $this->mustache->render(file_get_contents(__DIR__ . '/../../views/login-form.mustache'), []);
    }

    // Handle the login submission
    public function login(): string
    {
        $usernameOrEmail = $_POST['username_or_email'];
        $password = $_POST['password'];

        // Check required fields
        if (empty($usernameOrEmail) || empty($password)) {
            return "Username/Email and Password are required.";
        }

        // Assuming a User model exists with a method to validate user credentials
        $user = new User();

        // Fetch the user by username or email
        $userData = $user->getUserByUsernameOrEmail($usernameOrEmail);
        
        if ($userData) {
            // Verify the password against the hashed password stored in the database
            if (password_verify($password, $userData['password_hash'])) {
                session_start();
                $_SESSION['user'] = $userData['username']; // Store user details in the session
                return "Login successful. Welcome!";
            } else {
                return "Invalid username/email or password.";
            }
        } else {
            return "Invalid username/email or password.";
        }
    }
}
