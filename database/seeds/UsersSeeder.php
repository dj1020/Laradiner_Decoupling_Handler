<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        foreach (range(1, 3) as $i) {
            factory(App\User::class)->create([
                'name'     => "User $i",
                'password' => bcrypt('password')
            ]);
        }
    }
}
