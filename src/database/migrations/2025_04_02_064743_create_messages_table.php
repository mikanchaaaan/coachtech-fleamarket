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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users'); // 出品者
            $table->foreignId('receiver_id')->constrained('users'); // 購入者
            $table->foreignId('exhibition_id')->constrained('exhibitions'); // 商品ID
            $table->text('message'); // メッセージ内容
            $table->boolean('is_read')->default(false); // 既読/未読フラグ
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
        Schema::dropIfExists('messages');
    }
};
