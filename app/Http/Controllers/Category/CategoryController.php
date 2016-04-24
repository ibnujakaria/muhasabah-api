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
