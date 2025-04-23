<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('market_offers', function (Blueprint $table) {
            $table->decimal('available_amount', 18, 8)->default(0)->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('market_offers', function (Blueprint $table) {
            $table->dropColumn('available_amount');
        });
    }
};

