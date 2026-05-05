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
        $tables = ['wallets', 'categories', 'transactions', 'dreams', 'budgets', 'recurring_transactions'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'user_id')) {
                        $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['wallets', 'categories', 'transactions', 'dreams', 'budgets', 'recurring_transactions'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'user_id')) {
                        $table->dropForeign(['user_id']);
                        $table->dropColumn('user_id');
                    }
                });
            }
        }
    }
};
