<?php

use Illuminate\Database\Seeder;
use UHacWeb\Models\ApiKey;
use UHacWeb\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name' => 'John Eris',
                'last_name' => 'Villanueva',
                'email' => 'johneris.villanueva@coreproc.ph',
                'mobile_number' => '09753966346'
            ],
            [
                'first_name' => 'Mark Jayson',
                'last_name' => 'Fuentes',
                'email' => 'markjayson.fuentes@coreproc.ph',
                'mobile_number' => '09178436703'
            ],
            [
                'first_name' => 'Ivan',
                'last_name' => 'Bergonia',
                'email' => 'ivan.bergonia@coreproc.ph',
                'mobile_number' => '09363036428'
            ],
            [
                'first_name' => 'UHac',
                'last_name' => 'Test User',
                'email' => 'uhac@coreproc.ph',
                'mobile_number' => '09178436703'
            ],
        ];

        $password = 'uhac123';

        foreach ($users as $userInfo) {
            $user = User::create([
                'email' => $userInfo['email'],
                'password' => bcrypt($password)
            ]);

            $user->userProfile()->create([
                'first_name' => $userInfo['first_name'],
                'last_name' => $userInfo['last_name'],
            ]);

            $user->mobileNumber()->create([
                'mobile_number' => $userInfo['mobile_number']
            ]);

            $apiKey = ApiKey::make($user);

            $this->command->info('User created for \'' . $userInfo['email'] . '\' with password \'' . $password . '\' and API_KEY \'' . $apiKey->key . '\'.');
        }
    }
}
