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
        Schema::create('raw_imports', function (Blueprint $table) {
            $table->id();
            $table->string('assay_class');
            $table->integer('affiliation_id');
            $table->string('pubmed_id');
            $table->string('gene_symbol')->nullable();
            $table->boolean('approved')->default(false);
            $table->json('data');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_imports');
    }
};
