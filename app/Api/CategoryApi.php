<?php
namespace App\Api;

use App\Record;
use App\Category;

class CategoryApi
{

  public function getCategories()
  {
    return \JWTAuth::parseToken()->authenticate()->categories;
  }

  /**
  * So there are two parameteers
  * @param $values
  * @param $type
  *
  * The first method, contains the values of the new row of categories table
  * and the second is $type, which has a default value null
  *
  * if the $type equals to null, then we need to store only new category
  * it means that this category will contains another sub category
  * but if the $type is not null, this means we need to store new row of records table,
  * and assign the type as the $type passed by the user
  **/
  public function createNew(array $values, $type = null)
  {
    # this is just for confinience
    $values = (object) $values;

    $category = new Category;
    $category->user_id = $values->user_id;
    $category->name = $values->name;
    $category->save();

    # if the $type is not null
    if ($type) {
      # then create new record in records table
      $record = new Record;
      $record->user_id = $values->user_id;
      $record->category_id = $category->id;
      # we left the sub_category_id as null because this category will never
      # contains sub categories
      $record->type = $type;
      $record->save();
    }

    return $category;
  }

  public function destroy($id)
  {
    $category = \JWTAuth::parseToken()->authenticate()->categories()->find($id);

    if ($category) {
      # before we deleted the category, we need to also delete the sub-categories, and the records
      $category->subCategories()->delete();

      # get the records, and delete them (with the data)
      $records = Record::where('category_id', $category->id)->get();
      foreach ($records as $key => $record) {
        $record->recordData()->delete();
        $record->delete();
      }

      return $category->delete();
    }

    return false;
  }
}
