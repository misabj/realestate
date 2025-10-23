<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();

                $table->foreignId('category_id')
                      ->constrained('categories')
                      ->cascadeOnUpdate()
                      ->restrictOnDelete();

                // Naslovi
                $table->string('title');
                $table->string('title_sr')->nullable();
                $table->string('title_ru')->nullable();

                $table->string('slug')->unique();

                // Osnovni podaci
                $table->decimal('price', 12, 2)->nullable();
                $table->unsignedInteger('area')->nullable();
                $table->unsignedTinyInteger('rooms')->nullable();
                $table->unsignedTinyInteger('floor')->nullable();

                $table->string('city')->nullable();
                $table->string('address')->nullable();

                // Geolokacija (preciznije od float-a)
                $table->decimal('lat', 10, 7)->nullable();
                $table->decimal('lng', 10, 7)->nullable();

                // Opisi
                $table->text('description')->nullable();
                $table->text('description_sr')->nullable();
                $table->text('description_ru')->nullable();

                // Slike (čuvaj JSON; ti već šalješ niz)
                $table->json('images')->nullable();

                $table->boolean('is_published')->default(false);

                $table->timestamps();

                // Korisni indexi
                $table->index(['city', 'price']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};