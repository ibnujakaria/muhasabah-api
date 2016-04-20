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
    $this->middleware('jwt.auth');
  }

  public function getCategories()
  {
    $categories = $this->categoryApi->getCategories();

    return response()->json(compact('categories'));
  }

}
