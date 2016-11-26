<?php

use Illuminate\Database\Seeder;
use UHacWeb\Models\AccountType;

class AccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountTypes = [
            'Bank Account',
            'Debit / Credit Card',
            'Code Redemption'
        ];

        foreach ($accountTypes as $accountType) {
            AccountType::create([
                'name' => $accountType
            ]);
        }
    }
}
