<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');               // EN
                $table->string('name_sr')->nullable();
                $table->string('name_ru')->nullable();
                $table->string('slug')->unique();
                $table->string('type')->index();      // 'rent' | 'buy'
                $table->text('description')->nullable();      // EN
                $table->text('description_sr')->nullable();
                $table->text('description_ru')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};