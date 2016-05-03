<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      \DB::table('users')->truncate();
      
      $userApi = new \App\Api\UserApi();
      $userApi->newUser([
        'name'  =>  'Sample User 1',
        'email' =>  'sampleuser1@gmail.com',
        'google_id' => uniqid(),
        'avatar'  => 'http://demo.patternlab.io/images/fpo_avatar.png'
      ]);
    }
}
