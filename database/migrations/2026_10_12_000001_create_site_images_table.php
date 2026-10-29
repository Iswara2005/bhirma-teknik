<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya menyimpan gambar yang SUDAH diganti admin.
        // Slot yang belum diganti memakai gambar bawaan di config/site_images.php.
        Schema::create('site_images', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // contoh: hero, about_header
            $table->string('path');            // path di storage/public (atau URL penuh)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_images');
    }
};
