<?php

use Illuminate\Database\Seeder;
use UHacWeb\Models\AccountType;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \UHacWeb\Models\User::where('email', 'uhac@coreproc.ph')->first();

        $user->accounts()->create([
            'title' => 'OMEGA',
            'description' => 'This is a test UnionBank Account',
            'account_type_id' => AccountType::BANK_ACCOUNT,
            'account_number' => '101109944444',
        ]);
    }
}
