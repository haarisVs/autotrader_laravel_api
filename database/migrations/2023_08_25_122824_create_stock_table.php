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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->json('vehicle')->nullable();
            $table->json('advertiser')->nullable();
            $table->json('adverts')->nullable();
            $table->json('metadata')->nullable();
            $table->json('features')->nullable();
            $table->json('media')->nullable();
            $table->json('history')->nullable();
            $table->json('check')->nullable();
            $table->string('stockId')->nullable();
            $table->string('searchId')->nullable();
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
        Schema::dropIfExists('stock');
    }
};
