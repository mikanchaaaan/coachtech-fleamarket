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
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete(); // 評価者
            $table->foreignId('reviewee_id')->constrained('users')->cascadeOnDelete(); // 被評価者
            $table->foreignId('exhibition_id')->constrained('exhibitions')->cascadeOnDelete(); // 取引対象の商品
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete(); // 外部キー制約
            $table->tinyInteger('rating');
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
