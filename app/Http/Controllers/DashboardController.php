<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            $totalUsers = User::count();
            $totalSuperAdmins = User::role('super_admin')->count();
            $totalAdmins = User::role('admin')->count();
        } elseif ($user->hasRole('admin')) {
            $totalUsers = User::where('created_by_id', $user->id)
                              ->where('created_by_type', 'user')
                              ->count();
            $totalSuperAdmins = 0;
            $totalAdmins = 0;
        } else {
            $totalUsers = 0;
            $totalSuperAdmins = 0;
            $totalAdmins = 0;
        }
    
        return view('dashboard', compact('totalUsers', 'totalSuperAdmins', 'totalAdmins'));
    }
}
