<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

class DBController extends Controller
{
    public function index(Request $req, $db = 'geo1') {
        dd(config('session.driver'));
    }
}
