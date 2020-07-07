<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SystemImages;

class AdviserController extends Controller
{

    public function getSystemLogo(){
        return SystemImages::where('type','system_logo')->first();
    }  
}
