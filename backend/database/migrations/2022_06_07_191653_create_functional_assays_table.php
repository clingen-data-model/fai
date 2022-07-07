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
        Schema::create('functional_assays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliation_id');
            $table->unsignedBigInteger('publication_id');
            $table->string('gene_symbol')->nullable();
            $table->string('hgnc_id')->nullable();
            $table->boolean('approved')->default(false);
            $table->text('material_used')->nullable();
            $table->text('patient_derived_material_used')->nullable();
            $table->text('description')->nullable();
            // additional_document: Optional[List[str]] = None
            $table->text('read_out_description')->nullable();
            $table->text('range')->nullable();
            $table->text('normal_range')->nullable();
            $table->text('abnormal_range')->nullable();
            $table->text('indeterminate_range')->nullable();
            $table->text('validation_control_pathogenic')->nullable(); 
            $table->text('validation_control_benign')->nullable();
            $table->text('replication')->nullable();
            $table->text('statistical_analysis_description')->nullable();
            $table->text('significance_threshold')->nullable();
            $table->text('comment')->nullable();
            // $table->enum('range_type', ['quantitative', 'qualitative'])->nullable();
            $table->text('range_type')->nullable();
            $table->text('units')->nullable();
            $table->text("ep_biological_replicates")->nullable();
            $table->text("ep_technical_replicates")->nullable();
            $table->text("ep_basic_positive_control")->nullable();
            $table->text("ep_basic_negative_control")->nullable();
            $table->text("ep_proposed_strength_pathogenic")->nullable();
            $table->text("ep_propsed_strength_benign")->nullable();

            # Fields from ER 
            $table->json('field_notes')->nullable();
            $table->text('assay_notes')->nullable();

            $table->unsignedBigInteger('raw_import_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('publication_id')->references('id')->on('publications');
            $table->foreign('raw_import_id')->references('id')->on('raw_imports');
            $table->index(['hgnc_id']);
            $table->index(['affiliation_id']);
            $table->index(['publication_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('functional_assays');
    }
};
