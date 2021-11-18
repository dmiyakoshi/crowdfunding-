<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('introduction'); //自己紹介と企画の紹介
            $table->text('description_do'); //何を実現したいか
            $table->text('description_reason'); //なぜやろうと思ったか
            $table->text('how_use_money'); //お金の使いみち
            $table->date('relese_date'); //公開日
            $table->date('due_date'); //募集期間
            $table->boolean('relese_flag'); //募集を開始しているかのフラグ 1で募集状態
            $table->boolean('public'); //公開状態を判別 0だと非表示
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
