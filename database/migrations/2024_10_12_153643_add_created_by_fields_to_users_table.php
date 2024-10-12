<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->string('created_by_type', 50)->nullable();
            $table->index('created_by_id');
            $table->index('created_by_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['created_by_id']);
            $table->dropIndex(['created_by_type']);
            $table->dropColumn('created_by_id');
            $table->dropColumn('created_by_type');
        });
    }
};
