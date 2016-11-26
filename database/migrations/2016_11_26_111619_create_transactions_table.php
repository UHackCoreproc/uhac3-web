<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->nullable();
            $table->integer('source_account_id', false, true)->nullable();
            $table->integer('target_account_id', false, true)->nullable();
            $table->integer('target_account_type_id', false, true)->nullable();
            $table->enum('type', [
                'DEBIT',
                'CREDIT'
            ]);

            $table->string('mobile_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->decimal('amount', 19, 4)->nullable();
            $table->enum('status', [
                'PENDING',
                'FAILED',
                'SUCCESS'
            ]);
            $table->string('remarks')->nullable();
            $table->nullableTimestamps();

            $table->foreign('source_account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('target_account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('target_account_type_id')->references('id')->on('account_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}