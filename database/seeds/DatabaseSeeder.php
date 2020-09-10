<?php

use App\Models\App;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->user();
        $this->app();
    }

    private function user()
    {
        User::query()->create([
            'email' => 'moerain@qq.com',
            'name' => "muyu",
            'password' => password_hash('123', PASSWORD_DEFAULT),
        ]);
    }

    private function app()
    {
        App::query()->create([
            'name' => '钱迹',
            'key' => 'qianji',
            'position' => 1,
        ]);
    }
}
