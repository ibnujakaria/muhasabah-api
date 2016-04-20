<?php
namespace App\Api;

use App\Category;

class CategoryApi
{

  public function getCategories()
  {
    return \Auth::user()->categories;
  }

  public function createNew(array $values)
  {
    # this is just for confinience
    $values = (object) $values;

    $category = new Category;
    $category->user_id = $values->user_id;
    $category->name = $values->name;
    $category->save();

    return $category;
  }
}
