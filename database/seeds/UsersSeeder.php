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
                'name' => 'John Eris Villanueva',
                'email' => 'johneris.villanueva@coreproc.ph',
            ],
            [
                'name' => 'Mark Jayson Fuentes',
                'email' => 'markjayson.fuentes@coreproc.ph'
            ],
            [
                'name' => 'Ivan Bergonia',
                'email' => 'ivan.bergonia@coreproc.ph'
            ],
            [
                'name' => 'UHac Test User',
                'email' => 'uhac@coreproc.ph'
            ],
        ];

        foreach ($users as $user) {
            $user['password'] = bcrypt('hello123');
            $userModel = User::create($user);

            $apiKey = ApiKey::make($userModel);

            $this->command->info('User created for \'' . $user['email'] . '\' with password \'' . $user['password'] . '\' and API_KEY \'' . $apiKey->key . '\'.');
        }
    }
}
