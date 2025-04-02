<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiver_id')->constrained('users'); // 購入者（評価する側）
            $table->foreignId('seller_id')->constrained('users'); // 出品者（評価される側）
            $table->foreignId('exhibition_id')->constrained('exhibitions'); // 取引対象の商品
            $table->tinyInteger('rating')->comment('1〜5の評価'); // 1〜5の評価スコア
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
        Schema::dropIfExists('reviews');
    }
};
