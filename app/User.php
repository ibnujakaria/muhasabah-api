<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'google_id', 'avatar'
  ];

  protected $hidden = [
    'password'
  ];

  public function categories()
  {
    return $this->hasMany('App\Category');
  }

  public function records()
  {
    return $this->hasMany('App\Record');
  }

  public function scopeGoogleId($query, $id)
  {
    return $query->where('google_id', $id);
  }
}
