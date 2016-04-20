<?php
namespace App\Api;

use App\Category;

class CategoryApi
{

  public function getCategories()
  {
    return \JWTAuth::parseToken()->authenticate()->categories;
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

  public function destroy($id)
  {
    $category = \JWTAuth::parseToken()->authenticate()->categories()->find($id);

    if ($category) {
      return $category->delete();
    }

    return false;
  }
}
