<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE complains ADD FULLTEXT INDEX complains_complain_fulltext (complain)');
    }

    public function down(): void
    {
        Schema::table('complains', function (Blueprint $table) {
            $table->dropIndex('complains_complain_fulltext');
        });
    }
};
