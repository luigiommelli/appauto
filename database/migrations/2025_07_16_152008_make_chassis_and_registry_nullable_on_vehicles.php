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
    Schema::table('vehicles', function (Blueprint $table) {
        $table->string('chassis')->nullable()->change();
        $table->string('registry_number')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('vehicles', function (Blueprint $table) {
        $table->string('chassis')->nullable(false)->change();
        $table->string('registry_number')->nullable(false)->change();
    });
}
};
