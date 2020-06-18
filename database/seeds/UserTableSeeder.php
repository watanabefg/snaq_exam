<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 開発用ユーザーを定義
        App\User::create([
            'name' => "development user",
            'remember_token' => str_random(10),
            'settlement_date' => null,
            'shipment_date' => null,
            'repeat' => 0
        ]);

        // モデルファクトリーで定義したテストユーザーを 1 作成
        factory(App\User::class, 1)->create();
    }
}
