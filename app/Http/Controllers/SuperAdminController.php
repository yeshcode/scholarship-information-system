<?php
namespace App\Http\Controllers;

class SuperAdminController extends Controller
{
    // Dashboard: Fetch data for simple UI
    public function dashboard()
    {

         return view('super-admin.dashboard'); 
    }
}