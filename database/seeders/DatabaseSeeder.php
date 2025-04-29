<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('passwordUser'),
            'roles' => 'user',
        ]);
        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@Admin.com',
            'password' => bcrypt('passwordAdmin'),
            'roles' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Test Operator',
            'email' => 'test@Operator.com',
            'password' => bcrypt('passwordOperator'),
            'roles' => 'Operator',
        ]);


        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);

    }
}
