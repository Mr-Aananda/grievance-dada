<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('complains', function (Blueprint $table) {
            $table->foreignId('deleted_by')
                ->nullable()
                ->after('updated_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('deleted_by');
        });
    }

    public function down(): void
    {
        Schema::table('complains', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropIndex(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
    }
};
