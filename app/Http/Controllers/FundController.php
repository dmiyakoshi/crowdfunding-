<?php

namespace App\Http\Controllers;

use App\Consts\FundConst;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundController extends Controller
{
    /**
     * dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $plans = Plan::wherehas('supports',function($query){
            $query->where('plan_id', Auth::guard(FundConst::GUARD)->user()->id);
        })->get();

        return view('auth.fund.dashboard', compact('plans'));
    }
}