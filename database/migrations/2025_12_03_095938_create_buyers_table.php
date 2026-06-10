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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('code')->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country');
            $table->text('address')->nullable();
            $table->boolean('status')->default(true);
            $table->text('note')->nullable();
            $table->foreignId('user_id')
                ->nullable()
                ->comment('Creator')
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('updated_id')
                ->nullable()
                ->comment('Who make update')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
