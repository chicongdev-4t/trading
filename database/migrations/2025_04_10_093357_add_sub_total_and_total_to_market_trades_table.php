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
        Schema::table('market_trades', function (Blueprint $table) {
            $table->decimal('sub_total', 20, 8)->after('price');
            $table->decimal('total', 20, 8)->after('sub_total');
        });
    }

    public function down(): void
    {
        Schema::table('market_trades', function (Blueprint $table) {
            $table->dropColumn(['sub_total', 'total']);
        });
    }
};
