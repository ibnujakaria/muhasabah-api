<?php
namespace App\Api;

use App\User;
/**
* this class is used to provides apis for user table
*/
class UserApi
{

  public function getById($id)
  {
    return User::find($id);
  }

  public function getByGoogleId($googleId)
  {
    return User::googleId($googleId)->first();
  }

  public function newUser(array $values)
  {
    $user = User::create($values);

    # create new default categories
    $categories = [
      ['user_id' => $user->id, 'name' => 'Sholat'],
      ['user_id' => $user->id, 'name' => 'Puasa'],
      ['user_id' => $user->id, 'name' => 'Baca Al-Quran']
    ];

    # simpen categories
    \App\Category::insert($categories);

    return $user;
  }
}
