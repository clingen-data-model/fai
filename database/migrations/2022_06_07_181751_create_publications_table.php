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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('coding_system_id');
            $table->string('code');
            $table->timestamps();

            $table->unique(['coding_system_id', 'code']);
            $table->foreign('coding_system_id')->references('id')->on('coding_systems');
            $table->index('title');
            $table->index('coding_system_id');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publications');
    }
};
