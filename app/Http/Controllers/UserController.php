<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(){
        return auth()->user();
    }
}
