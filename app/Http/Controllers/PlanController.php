<?php

namespace App\Http\Controllers;

use App\Consts\UserConst;
use App\Consts\FundConst;
use App\Models\Method;
use App\Models\Photo;
use App\Models\Plan;
use App\Models\Gift;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plansPre = Plan::with('user')->where('public', 1)->latest()->paginate(10); //20は多い 16? 8は少ない
        // dd($plansPre);
        // $plansPre = $plansPre->setAtr();
        // // dd($plansPre);
        // $filtered = $plansPre->filter(function ($v) {
        //     if ($v['end_flag']) {                   //募集終了しているプロジェクトは表示させない
        //         return false;
        //     } else if ($v['relese_flag']) {         //募集開始ならば基準に満たしていないプロジェクトを除外する
        //         return $v['start_flag'] == true;
        //     } else {
        //         return true;
        //     }
        // })->values();
        // dd($filtered);

        // foreach ($filtered as $filter) {
        //     $plans[] = Plan::find($filter['id']);
        // }
        // $plans = $filtered;
        // dd($plans);

        // $plans = new LengthAwarePaginator(
        //     $filtered->forPage($filtered->page, 5),
        //     count($filtered),
        //     5,
        //     $filtered->page,
        //     array('path' => '/plans')
        // );

        // dd($plans);

        $query = Plan::query();
        $query->with('user')->where('public', 1);
        $query->where('due_date', '>=', now());
        // $query->where('relese_date', '<=', now());
        $plans = $query->latest()->paginate(10);
        // dd($plans);

        return view('plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $methods = Method::all();

        return view('plans.create', compact('methods'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $plan = new Plan($request->all());

        $plan->user_id = $request->user()->id;

        $plan->public = 0;

        $files = $request->file('file');
        // dd($request, $plan);
        DB::beginTransaction();

        try {
            $plan->save();

            foreach ($files as $file) {
                if (!$path = Storage::putFile('plans', $file)) {
                    throw new Exception('ファイルの保存に失敗しました。');
                }
                $photo = new Photo([
                    'name' => $file->getClientOriginalName(),
                    'path' => basename($path)
                ]);

                $photo->plan_id = $plan->id;

                $photo->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            if (isset($plan)) {
                $paths = $plan->image_paths;
                foreach ($paths as $path) {
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                    }
                }
            }

            DB::rollBack();
            return back()->withErrors('登録処理でエラーが発生しました');
        }

        return redirect()->route('plans.show', $plan)->with('notice', 'プロジェクト情報を登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        $gifts = Gift::where('plan_id', $plan->id)->get(); // プロジェクトのリターンを渡す

        if (Auth::guard(FundConst::GUARD)->check()) { //支援者であればsupportの情報がほしい
            $supports = $plan->supports->where('fund_id', Auth::guard(FundConst::GUARD)->user()->id);
            //プロジェクト紐付いている支援情報から支援者のidが一致しているものを選ぶ
        } else {
            $supports = [];
        }

        return view('plans.show', compact('plan', 'gifts', 'supports'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        $methods = Method::all();

        return view('plans.edit', compact('plan', 'methods'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        // dd($request);
        $plan->fill($request->all());
        // dd($plan);
        try {
            $plan->save();
        } catch (\Throwable $th) {
            return back()->withErrors('更新作業でエラーが発生しました');
        }

        return redirect()->route('plans.show', $plan)->with('notice', '情報を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        if (Auth::guard(UserConst::GUARD)->user()->cannot('delete', $plan)) {
            return redirect()->route('job_offers.show', $plan)
                ->withErrors('自分のプロジェクト以外は削除できません');
        }

        DB::beginTransaction();

        try {
            $paths = $plan->image_paths;
            foreach ($paths as $path) {
                if (!Storage::delete($path)) {
                    throw new Exception('ファイルの削除に失敗しました');
                }
            }
            $photos = $plan->photos();

            foreach ($photos as $photo) {
                $photo->delete();
            }

            $gifts = $plan->gifts();
            if (isset($gifts)) {
                foreach ($gifts as $gift) {
                    $path = $gift->image_path;
                    if (Storage::delete($path)) {
                        throw new Exception('ファイルの削除に失敗しました');
                    }

                    $supports = $gift->supports;
                    if (isset($supports)) {
                        foreach ($supports as $support) {
                            $support->delete();
                        }
                    }
                    $gift->delete();
                }
            }

            $plan->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors('プロジェクト削除処理でエラーが発生しました');
        }

        return redirect()->route('plans.index')->with('notice', 'プロジェクトを削除しました');
    }

    public function changePublic(Plan $plan)
    {
        $plan->public = 1;
        $plan->save();

        return redirect()->route('plans.show', $plan)->with('notice', 'プロジェクトを公開に変更しました');
    }
}
