<?php
namespace App\Api;

use App\Category;

class CategoryApi
{

  public function getCategories()
  {
    return \Auth::user()->categories;
  }
}
