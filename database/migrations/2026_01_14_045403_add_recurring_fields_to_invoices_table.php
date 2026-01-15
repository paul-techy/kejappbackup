<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->enum('recurring_interval', ['daily', 'monthly', 'yearly'])->nullable()->after('is_recurring');
            $table->date('recurring_start_date')->nullable()->after('recurring_interval');
            $table->date('recurring_end_date')->nullable()->after('recurring_start_date');
            $table->integer('recurring_parent_id')->nullable()->after('recurring_end_date');
            $table->date('last_generated_date')->nullable()->after('recurring_parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'is_recurring',
                'recurring_interval',
                'recurring_start_date',
                'recurring_end_date',
                'recurring_parent_id',
                'last_generated_date'
            ]);
        });
    }
};
