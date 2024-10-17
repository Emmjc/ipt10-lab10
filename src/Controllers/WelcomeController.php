<?php

namespace App\Controllers;

use App\Models\Supplier;
use App\Controllers\BaseController;

class WelcomeController extends BaseController
{
    
    public function index()
    {
        $template = 'welcome';
        $data = [
            'student' => 'Marcus Jeremy Mallari Cariño',
            'title' => 'IPT10 Laboratory Activity #10',
            'college' => 'College of Computer Studies',
            'university' => 'Angeles University Foundation',
            'location' => 'Angeles City, Pampanga, Philippines'
        ];
        $output = $this->render($template, $data);
        return $output;
    }
}