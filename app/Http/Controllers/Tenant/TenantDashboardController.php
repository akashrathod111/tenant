<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalUsers = User::where('created_by_id', $user->id)
            ->where('created_by_type', 'admin')
            ->count();

        return view('app.dashboard',compact('totalUsers'));
    }
}
