<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('functional_assays', function (Blueprint $table) {
            $table->json('additional_publication_ids')->nullable()->after('publication_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('functional_assays', function (Blueprint $table) {
            $table->dropColumn('additional_publication_ids');
        });
    }
};
