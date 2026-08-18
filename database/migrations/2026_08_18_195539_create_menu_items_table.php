<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();
            $table->string('name_lt');
            $table->string('name_en');
            $table->text('description_lt')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('art')->nullable();
            $table->string('tag')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['menu_category_id', 'is_active', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
