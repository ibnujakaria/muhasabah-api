<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Api\CategoryApi;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

  private $categoryApi;

  public function __construct(CategoryApi $categoryApi)
  {
    $this->categoryApi = $categoryApi;

    # setting up the middleware
    $this->middleware(['jwt.auth', 'jwt.refresh']);
  }

  public function getCategories()
  {
    $categories = $this->categoryApi->getCategories();

    return response()->json(compact('categories'));
  }

  public function storeCategory(Request $request)
  {
    $validator = \Validator::make($request->all(), [
      'name'  =>  'required|max:255',
      'records.*.type'  =>  'in:counter,checker',
      'records.*.name'  =>  'max:255'
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 500);
    }

    # if the form-data is valid
    $user = \JWTAuth::parseToken()->authenticate();

    # get the records from user input
    $records = $request->input('records', null);

    # create new category
    $category = $this->categoryApi->createNew([
      'user_id' =>  $user->id,
      'name'  =>  $request->input('name')
    ], $records);

    $message = "New category successfully created";

    return response()->json(compact('category', 'message'));
  }

  public function addRecords(Request $request, $category_id)
  {
    $category = $this->categoryApi->getById($category_id);
    # make sure that the category doesn't has sub
    if ($category->has_sub_category) {
      # if does, kill it!
      return $this->somethingWentWrong();
    }

    # make sure the category is belongs to the authenticated user
    if ($category->user_id === \JWTAuth::parseToken()->authenticate()->id) {
      return $this->somethingWentWrong();
    }

    # we need to validate the form data at first
    $validator = \Validator::make($request->all(), [
      'records' => 'required|array',
      'records.*' => 'required|array',
      'records.*.type'  =>  'required|in:counter,checker',
      'records.*.name'  =>  'required|max:255'
    ]);

    # if the validation fails
    if ($validator->fails()) {
      # return the errors
      $errors = $validator->errors();

      return response()->json(compact('errors'));
    }
    # okay, here we are. Now, just create the sub
    $result = $this->categoryApi->addRecordsToCategory($category, $request->input('records', null));

    return response()->json(['message' => 'New records has successfully created', $result]);
  }

  public function show ($id)
  {
    $category = $this->categoryApi->getById($id);

    return response()->json(compact('category'));
  }

  public function destroy($id)
  {
    if (!$this->categoryApi->destroy($id)){
      return $this->somethingWentWrong(); # check the parent controller
    }

    $message = "Category destroyed";

    return response()->json(compact('message'));
  }
}
