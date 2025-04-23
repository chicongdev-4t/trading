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
        Schema::table('account_changes', function (Blueprint $table) {
            // Đổi từ enum sang string
            $table->string('type')->change();
        });
    }

    public function down(): void
    {
        Schema::table('account_changes', function (Blueprint $table) {
            // Khôi phục enum ban đầu nếu cần
            $table->enum('type', ['Buy', 'Sell', 'create_offer', 'cancel_offer', 'deposit', 'withdraw', 'fee'])->change();
        });
    }
};
