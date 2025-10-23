<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'name_sr'))        $table->string('name_sr')->nullable()->after('name');
            if (!Schema::hasColumn('categories', 'name_ru'))        $table->string('name_ru')->nullable()->after('name_sr');
            if (!Schema::hasColumn('categories', 'description_sr')) $table->text('description_sr')->nullable()->after('description');
            if (!Schema::hasColumn('categories', 'description_ru')) $table->text('description_ru')->nullable()->after('description_sr');
        });
    }
    public function down(): void {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_sr','name_ru','description_sr','description_ru']);
        });
    }
};
