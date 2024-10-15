<?php

namespace App\Controllers;

use App\Models\User;
use Mustache_Engine;

class RegistrationController
{
    private $mustache;

    public function __construct()
    {
        $this->mustache = new Mustache_Engine();
    }

    // Display the registration form
    public function showForm()
    {
        // Render the Mustache view for the registration form
        echo $this->mustache->render(file_get_contents(__DIR__ . '/../../views/registration-form.mustache'), []);
    }

    // Handle the registration submission
    public function register()
    {
        // Validate the input
        $username = $_POST['username'];
        $email = $_POST['email'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password_confirm'];

        // Check required fields
        if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
            return $this->showFormWithError("All required fields must be filled.");
        }

        // Check password confirmation
        if ($password !== $passwordConfirm) {
            return $this->showFormWithError("Passwords do not match.");
        }

        // Check password length (minimum 8 characters)
        if (strlen($password) < 8) {
            return $this->showFormWithError("Password must be at least 8 characters long.");
        }

        // Check password complexity (contains numeric, non-numeric, and special characters)
        if (!preg_match('/\d/', $password)) {
            return $this->showFormWithError("Password must contain at least one numeric character.");
        }
        if (!preg_match('/[a-zA-Z]/', $password)) {
            return $this->showFormWithError("Password must contain at least one non-numeric character.");
        }
        if (!preg_match('/[!@#$%^&*()\-+]/', $password)) {
            return $this->showFormWithError("Password must contain at least one special character (!@#$%^&*-+).");
        }

        // If all checks pass, create the user
        $user = new User();
        $result = $user->register($username, $email, $firstName, $lastName, $password); // Use raw password here

        if ($result) {
            // Render the success view
            echo $this->mustache->render(file_get_contents(__DIR__ . '/../../views/registration-success.mustache'), []);
        } else {
            return $this->showFormWithError("Registration failed. Please try again or check if the username/email already exists.");
        }
    }

    private function showFormWithError($errorMessage)
    {
        // Render the registration form with an error message
        echo $this->mustache->render(file_get_contents(__DIR__ . '/../../views/registration-form.mustache'), ['error' => $errorMessage]);
    }
}
