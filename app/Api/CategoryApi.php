<?php
namespace App\Api;

use App\Record;
use App\Category;
use App\SubCategory;

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

  public function getById($id)
  {
    $category = \JWTAuth::parseToken()->authenticate()->categories()->find($id);

    # do this category is the parent? Or it has a subCategories
    # check it from the table record!
    # but, surely, we have it configured on the model, just do the checking
    if ($category->has_sub_category) {
      # attach the sub categories to the result
      $category->subCategories;
    }
    else {
      # otherwise, attach the $record to category result
      $category->record = Record::category($category->id)->whereNull('sub_category_id')->get(); # :)
    }

    return $category;
  }

  /**
  * @param $category | instance of \App\Category
  * @param $values | array
  * @param $values | string -> the type of record (counter or checkers)
  */
  public function newSub($category, array $values, $type)
  {
    # just make me more confident
    $values = (object) $values;
    # ignore everything. Just put the values to the sub categories table
    # dramatically
    $subCategory = new SubCategory;
    $subCategory->category_id = $category->id;
    $subCategory->name = $values->name;
    $subCategory->save();

    # create the new record with the $type value
    $record = new Record;
    $record->user_id = $category->user_id;
    $record->category_id = $category->id;
    $record->sub_category_id = $subCategory->id;
    $record->type = $type;
    $record->save();

    return $subCategory;
  }

  public function getSubCategory($category_id, $sub_category_id)
  {
    $subCategory = $this->getById($category_id)->subCategories()->find($sub_category_id);

    # attach the records of this sub category
    $subCategory->record = Record::with('recordData')->subCategory($category_id, $sub_category_id)->first();

    return $subCategory;
  }

  # destroy the category
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

  # destroy sub categories
  public function destroySub($category_id, $sub_category_id)
  {
    $subCategory = $this->getSubCategory($category_id, $sub_category_id);

    if ($subCategory) {
      # delete the record data at first
      $subCategory->record->recordData()->delete();
      # delete the record data
      $subCategory->record->delete();
      # then delete the sub category
      return $subCategory->delete();
    }

    return false;
  }
}
