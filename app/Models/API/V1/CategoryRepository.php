<?php

namespace App\Models\API\V1;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;

use App\Models\AppCategory;
use App\Models\AppCategoryTranslation;

class CategoryRepository extends Model
{

    public function getCategories($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $categories = AppCategory::join('app_category_translations','app_categories.id','app_category_translations.category_id')
                                        ->where('app_category_translations.language_code',$language)
                                        ->paginate(10);
            $data = array(
                'status_code' => '200',
                'message' => 'All category has been fetched successfully!!',
                'data' => $categories
            ); 
            return new SuccessResource($data);
            }catch(\Throwable $th){
                $data = array(
                    'status_code' => '400',
                    'message' => $th->getMessage(),
                    'data' => []
                );
            return new ErrorResource($data);
        }
    }

    public function createCategory($request)
    {
        try{
            $category = new AppCategory;
            $language  = isset($request->lang)?$request->lang:'en';

            if($request->has('logo')){
                $fileName = $request->file('logo')->getClientOriginalName();
                $path = $request->file('logo')->storeAs('categories', time().'_'.$fileName, 'public');
                $category->logo = $path;
            }
            $category->created_by = 1;
            $category->updated_by = 1;
            $category->deleted_by = 1;
            $category->save();
            
            $appCategoryTranslation = new AppCategoryTranslation;
            $appCategoryTranslation->category_id = $category->id;
            $appCategoryTranslation->language_code = $language;
            $appCategoryTranslation->name = $request->name;
            $appCategoryTranslation->desc = $request->desc;

            $appCategoryTranslation->created_by = 1;
            $appCategoryTranslation->updated_by = 1;
            $appCategoryTranslation->deleted_by = 1;
            $appCategoryTranslation->save();

            $data = array(
                'status_code' => '200',
                'message' => 'Category has been added successfully!!',
                'data' => $category
            ); 
            return new SuccessResource($data);
            }catch(\Throwable $th){
                $data = array(
                    'status_code' => '400',
                    'message' => $th->getMessage(),
                    'data' => []
                );
            return new ErrorResource($data);
        }
        
    }

    public function getCategoryById($request, $id)
    {
        try{
            $category = AppCategory::findOrFail($id);

            $language  = isset($request->lang)?$request->lang:'en';
            
            $appCategoryTranslation = AppCategoryTranslation::where([
                ['category_id',$category->id],
                ['language_code', $language]
            ])->first();
            $appCategoryTranslation->logo = $category->logo;
            if(is_null($appCategoryTranslation)){
                $data = array(
                    'status_code' => '200',
                    'message' => 'Category details not available in '.$language.' language',
                );
            return new ErrorResource($data);
            }
            $data = array(
                'status_code' => '200',
                'message' => 'Category details has been fetched successfully!!',
                'data' => $appCategoryTranslation
            ); 
            return new SuccessResource($data);
        }catch(\Throwable $th){
            $data = array(
                'status_code' => '400',
                'message' => $th->getMessage(),
                'data' => []
            );
        return new ErrorResource($data);
        }
        
    }

    public function updateCategory($request,$id)
    {
        try{
            $category = AppCategory::findOrFail($id);

            $language  = isset($request->lang)?$request->lang:'en';

            if($request->has('logo')){
                $fileName = $request->file('logo')->getClientOriginalName();
                $path = $request->file('logo')->storeAs('categories', time().'_'.$fileName, 'public');
                $category->logo = $path;
            }
            $category->created_by = 1;
            $category->updated_by = 1;
            $category->deleted_by = 1;
            $category->save();
            
            $appCategoryTranslation = AppCategoryTranslation::where([
                ['category_id',$category->id],
                ['language_code', $language]
            ])->first();
            $messages = "Category Details has been updated successfully!!";

            if(is_null($appCategoryTranslation)){
                $appCategoryTranslation = new AppCategoryTranslation;
                $messages = "Category ".$language." information added successfully!!";
            }

            $appCategoryTranslation->category_id = $category->id;
            $appCategoryTranslation->language_code = $language;
            $appCategoryTranslation->name = $request->name;
            $appCategoryTranslation->desc = $request->desc;

            $appCategoryTranslation->created_by = 1;
            $appCategoryTranslation->updated_by = 1;
            $appCategoryTranslation->deleted_by = 1;

            $appCategoryTranslation->save();
            $data = array(
                'status_code' => '200',
                'message' => $messages,
                'data' => $category
            ); 
            return new SuccessResource($data);
        }catch(\Throwable $th){
            $data = array(
                'status_code' => '400',
                'message' => $th->getMessage(),
                'data' => []
            );
        return new ErrorResource($data);
        }
        
    }

    public function deleteCategory($request, $id)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $appCategory = AppCategory::where('id',$id)->first();
            if(is_null($appCategory)){
                $data = array(
                    'status_code' => '400',
                    'message' => 'Category not Found',
                    'data' => []
                );
                return new ErrorResource($data);
            }
            $appCategoryTranslation = AppCategoryTranslation::where([
                ['category_id',$appCategory->id],
                ['language_code', $language]
            ])->first();

            if(is_null($appCategoryTranslation)){
                $data = array(
                    'status_code' => '400',
                    'message' => 'Category details not found on '.$language.' language',
                    'data' => []
                );
                return new ErrorResource($data);
            }
            $appCategoryTranslation->delete();
            $data = array(
                'status_code' => '200',
                'message' => 'Category has been deleted successfully!!',
                'data' => []
            ); 
            return new SuccessResource($data);
            }catch(\Throwable $th){
                $data = array(
                    'status_code' => '400',
                    'message' => $th->getMessage(),
                    'data' => []
                );
            return new ErrorResource($data);
        }
        
    }
    
}
