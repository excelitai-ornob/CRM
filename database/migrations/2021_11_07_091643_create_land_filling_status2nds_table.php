<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandFillingStatus2ndsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
Schema::create('land_filling_status2nds', function (Blueprint $table) {
    $table->id();
    $table->bigInteger('user_id');
    $table->bigInteger('land_filling_money');
    $table->string('land_filling_money_payment_type');
    $table->string('land_filling_money_paid')->nullable();
    $table->string('land_filling_money_due')->nullable();
    $table->dateTime('land_filling_money_paid_date')->nullable();
    $table->dateTime('land_filling_money_due_date')->nullable();
    $table->text('land_filling_money_note')->nullable();
    $table->boolean('approval')->default(1);
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
        Schema::dropIfExists('land_filling_status2nds');
    }
}
