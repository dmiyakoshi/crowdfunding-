<?php

namespace App\Http\Controllers;

use App\Consts\FundConst;
use App\Models\Gift;
use App\Models\Plan;
use App\Models\Support;
use Exception;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function create(Plan $plan, Gift $gift)
    {
        // dd($plan->releseFlag, $plan->startFlag, $plan->endFlag);
        if ($plan->releseflag) {  //プロジェクトが募集しているならif文で確認をする 募集開始していないなら確認なし
            if (!$plan->startFlag || $plan->endFlag || ($gift->limited_befor == 1)) { //プロジェクトがすでに募集終了であるか、募集開始で基準にみたないものは支援不可
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
        if ($plan->releseflag) {  //プロジェクトが募集しているならif文で確認をする 募集開始していないなら確認なし
            if (!$plan->startFlag || $plan->endFlag || ($gift->limited_befor == 1)) { //プロジェクトがすでに募集終了であるか、募集開始で基準にみたないものは支援不可
                return back()->withErrors('このプロジェクトは支援できません');
            }
        }

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
            if (($plan->total - $support->money) > 0.1 || ($plan->total - $support->money) > $plan->goal) {
                $support->delete();
            } else {
                throw new Exception("現在支援情報は削除できません");
            }
        } catch (\Exception $e) {
            return back()->withErrors('支援情報の削除に失敗しました');
        }

        return redirect()->route('plans.show', $plan)->with('notice', '支援情報を削除しました');
    }
}
