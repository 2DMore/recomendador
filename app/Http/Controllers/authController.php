<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class authController extends Controller
{
    function accion_login() {
        include ('views/auth/login.php');
    }
    
    function accion_logout() {
        //delete credentials and authentication
    }
}
