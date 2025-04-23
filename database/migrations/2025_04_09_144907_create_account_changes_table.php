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
        Schema::create('account_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('currency');
            $table->enum('type', ['Buy', 'Sell','create_offer', 'cancel_offer', 'deposit', 'withdraw', 'fee']);
            $table->decimal('before_balance', 18, 8)->default(0);
            $table->decimal('change', 20, 8)->default(0);
            $table->decimal('after_balance', 20, 8)->default(0);
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_changes');
    }
};
