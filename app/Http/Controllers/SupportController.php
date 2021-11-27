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
        if ($plan->releseflag ) { //プロジェクトが募集しているならif文 true
            if (!$plan->startFlag || $plan->endFlag) { //プロジェクトがすでに募集終了であるか、募集開始で基準にみたないものは支援不可
                return back()->withErrors('このプロジェクトは支援できません');
            } else {
                //none
            }
        } else {
            //none
        }
        return view('plans.supports.create', compact('plan', 'gift'));
    }

    public function store(Plan $plan, Gift $gift)
    {

        $support = new Support();

        $support->plan_id = $plan->id;

        if (Auth::guard(FundConst::GUARD)->check()) {
            $support->fund_id = Auth::guard(FundConst::GUARD)->user()->id;
        }

        if (isset($gift)) {
            $support->gift_id = $gift->id;
            $support->money = $gift->price;
        }
        // dd($support);
        try {
            $support->save();
        } catch (\Throwable $th) {
            return back()->withErrors('支援情報の登録に失敗しました');
        }

        return redirect()->route('plans.show', $plan)->with('notice', '支援情報を登録しました');
    }

    public function destroy(Plan $plan, Support $support)
    {
        try {
            $support->delete();
        } catch (\Throwable $th) {
            return back()->withErrors('支援情報の削除に失敗しました');
        }

        return redirect()->route('plans.show', $plan)->with('notice', '支援情報を削除しました');
    }
}
