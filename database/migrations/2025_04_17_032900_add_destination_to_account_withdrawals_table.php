<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('account_withdrawals', function (Blueprint $table) {
            $table->json('destination')->nullable(); // Thêm cột destination kiểu JSON
        });
    }

    public function down()
    {
        Schema::table('account_withdrawals', function (Blueprint $table) {
            $table->dropColumn('destination'); // Xóa cột nếu rollback
        });
    }
};
