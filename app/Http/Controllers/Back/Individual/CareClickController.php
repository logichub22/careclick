<?php

namespace App\Http\Controllers\Back\Individual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareClickController extends Controller
{
    public function index(){
        $user = Auth::user();
        $tokenIsValid = True;

        return view('back/individual/careclick/index', compact('user', 'tokenIsValid'));
    }
}
