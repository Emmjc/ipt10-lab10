<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class User extends BaseModel
{
  

    // Check if username or email already exists
    private function userExists($username, $email)
    {
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch() !== false; // Return true if user exists
    }

    // Register a new user
    public function register($username, $email, $firstName, $lastName, $password)
    {
        // Check if user already exists
        if ($this->userExists($username, $email)) {
            return false; // User already exists
        }

        // Hash the password before saving it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert user data into the users table
        $sql = "INSERT INTO users (username, email, first_name, last_name, password_hash, created_at) 
                VALUES (:username, :email, :first_name, :last_name, :password_hash, NOW())";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':password_hash', $hashedPassword);
            
            if ($stmt->execute()) {
                return true; // User registered successfully
            } else {
                error_log("Failed to execute statement: " . implode(", ", $stmt->errorInfo())); // Log errors
                return false; // Return false if registration fails
            }
        } catch (PDOException $e) {
            // Log the error message for debugging purposes
            error_log("Database insert error: " . $e->getMessage());
            return false; // Return false if registration fails
        }
    }

    // Login a user
    public function login($usernameOrEmail, $password)
    {
        // SQL query to get user by username or email
        $sql = "SELECT * FROM users WHERE username = :usernameOrEmail OR email = :usernameOrEmail";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':usernameOrEmail', $usernameOrEmail);
        
        try {
            $stmt->execute();
            $user = $stmt->fetch();

            // Verify the password if user exists
            if ($user && password_verify($password, $user['password_hash'])) {
                return $user; // Return user data on successful login
            }
        } catch (PDOException $e) {
            // Handle query error
            error_log("Database query error: " . $e->getMessage());
            return false; // Return false on authentication failure
        }

        return false; // Authentication failed
    }
    public function getAllUsers()
    {
        $sql = "SELECT id, first_name, last_name, email FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
