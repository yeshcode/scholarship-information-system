<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinatorController extends Controller
{
    public function dashboard() { return view('coordinator.dashboard'); }
}
