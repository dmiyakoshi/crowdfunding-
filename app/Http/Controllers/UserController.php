<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $plans = Plan::latest()
            ->with('supports') 
            ->Myplan()
            ->paginate(5);

        return view('auth.user.dashboard', compact('plans'));
    }
}
