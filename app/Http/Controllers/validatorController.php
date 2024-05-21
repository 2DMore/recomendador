<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class validatorController extends Controller
{
    function accion_nuevos() {
        include ('views/validator/newListView.php');
    }
    
    function accion_validados() {
        include ('views/validator/validationListView.php');
    }
}
