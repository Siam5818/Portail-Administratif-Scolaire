<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\AuthService;

class AuthController extends Controller
{
    protected $authservice;

    public function __construct()
    {
        $this->authservice = new AuthService();
    }
}
