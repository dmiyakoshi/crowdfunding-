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
        $plans = Plan::with('user')->latest()->Paginate(12); //20は多い 16? 8は少ない

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
        $supports = "";

        $gifts = Gift::where('plan_id', $plan->id)->get(); // プロジェクトのリターンを渡す

        if (Auth::guard(FundConst::GUARD)->check()) {
            $supports = $plan->supports()
                ->where('plan_id', Auth::guard(FundConst::GUARD)->user()->id);
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
        $plan->fill($request->all());
// dd($plan);
        try {
            $plan->save();
        } catch (\Throwable $th) {
            return back()->withErrors('更新作業でエラーが発生しました');
        }

        return redirect()->route('plans.show', $plan)->with('notice', '情報を登録しました');
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

        try {
            $plan->delete();
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors('プロジェクト削除処理でエラーが発生しました');
        }

        return redirect()->route('plans.index')
            ->with('notice', '求人情報を削除しました');
    }
}
