<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('action'); // 個数変更、登録、削除などのアクション名
            $table->integer('previous_quantity')->nullable(); // アクションが個数変更の場合、変更前の個数を保存
            $table->integer('new_quantity')->nullable(); // アクションが個数変更の場合、変更後の個数を保存
            $table->timestamp('changed_at')->nullable(); // 変更日時を保存
            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_histories');
    }
}
