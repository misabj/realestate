<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $t) {
            $t->boolean('is_published')->default(false)->after('category_id');
            $t->index('is_published');
            $t->string('title_sr')->nullable();
            $t->string('title_ru')->nullable();
            $t->text('description_sr')->nullable();
            $t->text('description_ru')->nullable();
            $t->string('city_sr')->nullable();
            $t->string('city_ru')->nullable();
            $t->string('address_sr')->nullable();
            $t->string('address_ru')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $t) {
            $t->dropColumn([
                'title_sr','title_ru','description_sr','description_ru',
                'city_sr','city_ru','address_sr','address_ru',
            ]);
        });
    }
};