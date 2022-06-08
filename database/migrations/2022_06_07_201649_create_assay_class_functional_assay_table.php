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
        Schema::create('assay_class_functional_assay', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assay_class_id');
            $table->unsignedBigInteger('functional_assay_id');
            $table->timestamps();

            $table->foreign('functional_assay_id')->references('id')->on('functional_assays');
            $table->foreign('assay_class_id')->references('id')->on('assay_classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assay_class_functional_assay');
    }
};
