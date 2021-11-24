<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use App\Models\Photo;
use App\Models\Plan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // none
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Plan $plan)
    {
        return view('plans.gifts.create', compact('plan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Plan $plan)
    {
        $gift = new Gift($request->all());
        $gift->plan_id = $plan->id;

        $file = $request->file('file');
        // dd($request->file('file'), $request, $files);

        DB::beginTransaction();

        try {
            $gift->save();

            //             foreach ($files as $file) { //画像を複数に変更ならこちらにする
            // dd($file);
            //                 if (!$path = Storage::putFile('gifts' , $file)) {
            //                     return back()->withErrors('画像の登録に失敗しました');
            //                 }
            // dd($path);
            //                 $photo = new Photo([
            //                     'gift_id' => $gift->id,
            //                     'name' => $file->getClientOriginalName(),
            //                     'path' => basename($path)
            //                 ]);
            // dd($photo);
            //                 $photo->save();
            //             }

            if (!$path = Storage::putFile('gifts', $file)) {
                return back()->withErrors('画像の登録に失敗しました');
            }

            $photo = new Photo([
                'name' => $file->getClientOriginalName(),
                'path' => basename($path),
            ]);

            $photo->gift_id = $gift->id;

            $photo->save();

            DB::commit();
        } catch (\Exception $e) {
            if (isset($gift)) {
                $paths = $gift->getImageUrls();
                foreach ($paths as $path) {
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                    }
                }
            }

            DB::rollBack();
            return back()->withErrors('リターンの登録に失敗しました');
        }

        return redirect()->route('plans.show', compact('plan'))->with('notice', 'リターンを登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // none
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Gift $gift)
    {
        return view('plans.gifts.edit', compact('gift'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gift $gift)
    {
        $plan = Plan::find($gift->plan_id);

        $gift->fill($request->all());

        try {
            $gift->save();
        } catch (\Throwable $th) {
            back()->withErrors('リターン情報の更新に失敗しました');
        }

        return redirect()->route('plans.show', compact('plan'))->with('notice', 'リターンの情報を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gift $gift)
    {
        $plan = Plan::find($gift->plan_id);

        $supports = $gift->supports();

        DB::beginTransaction();

        try {
            $path = $gift->image_path;
            if (!Storage::delete($path)) {
                throw new Exception('ファイルの削除に失敗しました');
            }
            $photo = Photo::where('gift_id', $gift->id);
            $photo->delete();

            foreach ($supports as $support) {
                $support->delete();
            }

            $gift->delete();

            DB::commit();
        } catch (\Throwable $th) {
            return back()->withErrors('リターン情報の削除に失敗しました');
        }

        return redirect()->route('plans.show', compact('plan'))->with('notice', 'リターン情報を削除しました');
    }
}
