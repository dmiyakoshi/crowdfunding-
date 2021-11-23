<?php

namespace App\Http\Controllers;

use App\Consts\FundConst;
use App\Models\Gift;
use App\Models\Plan;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function create(Plan $plan, Gift $gift)
    {
        return view('plans.supports.create', compact('plan', 'gift'));
    }

    public function store(Plan $plan, Gift $gift)
    {
        $support = new Support();

        $support->plan_id = $plan->id;
        if (Auth::guard(FundConst::GUARD)->check()) {
            $support->fund_id = auth()->user()->id;
        }

        if (isset($gift)) {
            $support->gift_id = $gift->is;
            $support->money = $gift->price;
        }

        try {
            $support->save();
        } catch (\Throwable $th) {
            return back()->withErrors('支援情報の登録に失敗しました');
        }

        return redirect()->route('plans.show', $plan)->with('notice', '支援情報を登録しました');
    }

    public function destroy()
    {
        //
    }
}
