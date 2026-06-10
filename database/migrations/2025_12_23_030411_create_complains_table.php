<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['manual', 'complain'])->default('complain');
            $table->foreignId('complain_type_id')
                ->constrained('complain_types')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->foreignId('buyer_id')
                ->nullable()
                ->constrained('buyers')
                ->nullOnDelete();

            $table->text('manual_category')->nullable();

            $table->date('date');

            $table->string('name')->nullable();
            $table->string('ps', 100)->nullable();
            $table->string('po', 100)->nullable();
            $table->string('cap', 100)->nullable();
            $table->string('line_floor', 255)->nullable();
            $table->string('style_order', 255)->nullable();

            $table->integer('quantity')->nullable();
            $table->decimal('amount', 15, 2)->nullable();

            $table->text('complain');

            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])
                ->default('pending');

            // Video meta
            $table->integer('video_count')->default(0);
            $table->boolean('has_videos')->default(false);

            $table->text('note')->nullable();
            $table->text('status_note')->nullable();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('updated_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('complain_type_id');
            $table->index('category_id');
            $table->index('buyer_id');
            $table->index('date');
            $table->index('status');
            $table->index(['status', 'complain_type_id']);
            $table->index(['status', 'date']);
            $table->index(['complain_type_id', 'date']);
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');

            $table->index(['ps', 'date']);
            $table->index(['po', 'date']);
            $table->index(['type', 'date']);
            $table->index(['ps', 'po', 'date']);
            $table->index(['date', 'complain_type_id']);
            $table->index(['complain_type_id', 'type']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complains');
    }
};
