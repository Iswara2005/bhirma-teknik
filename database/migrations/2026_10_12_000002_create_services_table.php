<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // "Komputer dan Perlengkapannya"
            $table->string('label', 60);                  // nama pendek di badge: "Komputer"
            $table->string('slug')->unique();             // dipakai di URL, tetap walau label diubah
            $table->string('image');                      // path di storage/public (atau URL penuh)
            $table->text('desc');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
