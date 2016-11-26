<?php

use Illuminate\Database\Seeder;
use UHacWeb\Models\ApiKey;
use UHacWeb\Models\Country;
use UHacWeb\Models\User;
use UHacWeb\Models\AccountType;

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

        $faker = Faker\Factory::create();
        $password = 'uhac123';

        foreach ($users as $userInfo) {
            $user = User::create([
                'first_name' => $userInfo['first_name'],
                'last_name' => $userInfo['last_name'],
                'email' => $userInfo['email'],
                'password' => bcrypt($password),
                'sex' => 'm',
                'birthday' => $faker->date,
                'device_id' => $faker->uuid
            ]);

            $defaultAddress = $user->addresses()->create([
                'label' => 'Default',
                'address_1' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip_code' => $faker->postcode,
                'country_id' => Country::PHILIPPINES
            ]);

            $user->default_address_id = $defaultAddress->id;
            $user->save();

            $user->mobileNumber()->create([
                'mobile_number' => $userInfo['mobile_number']
            ]);

            $user->accounts()->create([
                'title' => $faker->creditCardType,
                'description' => $faker->text,
                'account_type_id' => AccountType::orderByRaw('RAND()')->limit(1)->first()->id,
                'account_number' => $faker->creditCardNumber,
            ]);

            $apiKey = ApiKey::make($user);

            $this->command->info('User created for \'' . $userInfo['email'] . '\' with password \'' . $password . '\' and API_KEY \'' . $apiKey->key . '\'.');
        }
    }
}
