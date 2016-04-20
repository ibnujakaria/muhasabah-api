<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{

  protected $appends = [
    'title'
  ];


  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function recordData()
  {
    return $this->hasMany('App\RecordData');
  }

  public function scopeCategory($query, $category_id)
  {
    return $query->where('category_id', $category_id);
  }

  public function scopeSubCategory($query, $category_id, $sub_category_id)
  {
    return $query->category($category_id)->where('sub_category_id', $sub_category_id);
  }

  public function getTitleAttribute()
  {
    $title = null;

    if ($this->sub_category_id) {
      $title = \App\SubCategory::find($this->sub_category_id)->name;
    }
    else {
      $title = \App\Category::find($this->category_id)->name;
    }

    return $title;
  }
}
