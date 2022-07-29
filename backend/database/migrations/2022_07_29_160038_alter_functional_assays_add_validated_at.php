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
        Schema::table('functional_assays', function (Blueprint $table) {
            $table->datetime('validated_at')->nullable();
            $table->index('validated_at');
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
            $table->dropIndex(['validated_at']);
            $table->dropColumn('validated_at');
        });
    }
};
