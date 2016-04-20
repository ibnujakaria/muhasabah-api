<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;

use App\Category;
use App\Http\Requests;
use App\Api\CategoryApi;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
  private $categoryApi;

  public function __construct(CategoryApi $categoryApi)
  {
    $this->categoryApi = $categoryApi;

    $this->middleware('jwt.auth');
    $this->middleware('jwt.refresh');
  }

  public function index($category_id)
  {
    $category = $this->categoryApi->getById($category_id);
  }

  public function store(Request $request, Category $category)
  {
    # make sure that the category doesn't has sub
    if (!$category->has_sub_category) {
      # if no, kill it!
      return $this->somethingWentWrong();
    }

    # make sure the category is belongs to the authenticated user
    if ($category->user_id === \JWTAuth::parseToken()->authenticate()->id) {
      return $this->somethingWentWrong();
    }

    # we need to validate the form data at first
    $validator = \Validator::make($request->all(), [
      'name'  => 'required|max:255',
      'type'  => 'required|in:counter,checker'
    ]);

    # if the validation fails
    if ($validator->fails()) {
      # return the errors
      $errors = $validator->errors();

      return response()->json(compact('errors'));
    }


    # okay, here we are. Now, just create the sub
    $this->categoryApi->newSub($category, [
      'name'  => $request->input('name')
    ], $request->input('type'));

    return response()->json(['message' => 'New Sub Category has successfully created']);
  }

  public function show($category_id, $sub_category_id)
  {
    # get sub category
    $subCategory = $this->categoryApi->getSubCategory($category_id, $sub_category_id);

    return response()->json(compact('subCategory'));
  }
}
