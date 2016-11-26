<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAccountsTableVerifyCard extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->boolean('paymaya_is_verified')->default(false);
            $table->string('paymaya_verify_url', 512)->nullable();
            $table->string('paymaya_card_type', 10)->nullable();
            $table->string('paymaya_card_mask', 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
