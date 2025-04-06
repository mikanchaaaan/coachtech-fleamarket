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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete(); // 出品者
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete(); // 購入者
            $table->foreignId('exhibition_id')->constrained('exhibitions')->cascadeOnDelete(); // 商品ID
            $table->boolean('is_active')->default(true); // 取引中フラグ
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
        Schema::dropIfExists('transactions');
    }
};
