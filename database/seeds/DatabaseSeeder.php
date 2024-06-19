<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //他のシーダーの呼び出し。
        $this->call(UsersTableSeeder::class);
        $this->call(SubjectsTableSeeder::class);
    }
}

//シーディングの基本的な流れ
//DatabaseSeederクラスを通じてすべてのシーダーを実行する
//シーディングする際、基本的にすべての個別のseedsはここを通り実行される。
//個別でシーディングる際は基本的に書かなくても実行されるが、一度にすべてをシーディングしたい場合は、ここで呼び出しを行う。
