<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'name'
    ];

    protected $hidden = [
      'user_id', 'created_at', 'updated_at'
    ];

    protected $appends = [
      'has_sub_category'
    ];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function subCategories()
    {
      return $this->hasMany('App\SubCategory');
    }

    public function getHasSubCategoryAttribute()
    {
      return $record = Record::with('recordData')->where('category_id', $this->id)->whereNull('sub_category_id')->count() < 1;
    }
}
