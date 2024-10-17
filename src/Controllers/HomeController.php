<?php

namespace App\Controllers;

use App\Models\User;
use Mustache_Engine;

class HomeController extends BaseController
{
    private $mustache;

    public function __construct()
    {
        $this->mustache = new Mustache_Engine();
    }

    public function welcome()
    {
        session_start();
        if (!isset($_SESSION['is_logged_in'])) {
            header("Location: /login-form");
            exit();
        }

        $userModel = new User();
        $users = $userModel->getAllUsers(); // implement this method in User model

        echo $this->mustache->render(file_get_contents(__DIR__ . '/../../views/welcome.mustache'), ['users' => $users]);
    }
}
