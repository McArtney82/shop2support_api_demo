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
        if (!Schema::hasTable('benificiary_retailer')) {
            Schema::create('benificiary_retailer', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('benificiary_id');
                $table->unsignedBigInteger('retailer_id');
                $table->timestamps();

                $table->foreign('benificiary_id')->references('id')->on('benificiaries')->onDelete('cascade');
                $table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('benificiary_retailer');
    }
};
