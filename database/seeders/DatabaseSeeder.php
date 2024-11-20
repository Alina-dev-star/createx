<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\CoursesTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\ModulesTableSeeder;
use Database\Seeders\LessonsTableSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        try {
            $this->call(RolesTableSeeder::class);

            // Проверяем, существует ли уже пользователь с таким email
            if (User::where('email', 'test@example.com')->doesntExist()) {
                User::create([
                    'name' => 'Test User',
                    'surname' => 'User',
                    'patronymic' => 'Testovich',
                    'birth_date' => '1990-01-01',
                    'phone' => '+1234567890',
                    'email' => 'test@example.com',
                    'password' => Hash::make('12345678'),
                    'role_id' => 1, // Укажите значение для role_id
                ]);
            }

            $this->call([
                CoursesTableSeeder::class,
                ModulesTableSeeder::class,
                LessonsTableSeeder::class,
            ]);
            
        } catch (\Exception $e) {
            $this->command->error('Ошибка при заполнении таблицы: ' . $e->getMessage());
        }
    }
}


// Чтобы использовать этот сидер, вам нужно добавить его в основной сидер DatabaseSeeder
// Затем вы можете запустить сидер с помощью команды:
// php artisan db:seed
