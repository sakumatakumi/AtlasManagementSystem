<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'over_name' => '若林',
                'under_name' => '正恭',
                'over_name_kana' => 'ワカバヤシ',
                'under_name_kana' => 'マサヤス',
                'mail_address' => 'wakasama@mail',
                'sex' => '1',
                'birth_day' => '1978-09-20',
                'role' => '1',
                'password' => bcrypt('wakasama'),
                'created_at' => now()
            ],
        ]);
    }
}
