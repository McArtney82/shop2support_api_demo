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
        if (!Schema::hasTable('retailers')) {
            Schema::create('retailers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('url');
                $table->string('short_text');
                $table->string('affiliate_network');
                $table->string('long_text');
                $table->boolean('link_status')->default(true);
                $table->boolean('featured')->default(false);
                $table->dateTime('last_verified')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('retailers');
    }
};
