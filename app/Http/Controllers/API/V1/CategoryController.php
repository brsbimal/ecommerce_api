<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCategoryRequest;

use App\Models\API\V1\CategoryRepository;

use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->manageCategory = new CategoryRepository();

    }

    public function getCategories(Request $request)
    {
        return $this->manageCategory->getCategories($request);
    }

    public function createCategory(CreateCategoryRequest $request)
    {
        return $this->manageCategory->createCategory($request);
    }

    public function getCategoryById(Request $request, $id){
        return $this->manageCategory->getCategoryById($request,$id);
    }

    public function updateCategory(CreateCategoryRequest $request,$id)
    {
        return $this->manageCategory->updateCategory($request,$id);
    }

    public function deleteCategory(Request $request, $id)
    {
        return $this->manageCategory->deleteCategory($request, $id);
    }

}
