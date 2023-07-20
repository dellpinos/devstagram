<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function index()
    {

        dd(auth()->user());

        dd('desde el muro');
    }
}

