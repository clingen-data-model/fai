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
        Schema::create('snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('functional_assay_id');
            $table->unsignedInteger('version');
            $table->json('data');
            $table->timestamps();

            $table->foreign('functional_assay_id')->references('id')->on('functional_assays');
            $table->index('version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snapshots');
    }
};
