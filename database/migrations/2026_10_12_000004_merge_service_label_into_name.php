<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Bidang usaha cukup punya SATU nama. Nama pendek (label) yang selama ini
 * tampil sebagai badge menjadi nama usaha, lalu kolom label dihapus.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->update(['name' => DB::raw('label')]);

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('label', 60)->default('')->after('name');
        });

        DB::table('services')->update(['label' => DB::raw('name')]);
    }
};