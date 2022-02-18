<?php

namespace App\Models\API\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;

use App\Models\AppCategory;
use App\Models\AppProduct;
// use App\Models\AppProductTranslation;

use DB;

class ManageShopRepository extends Model
{
    public function getCategories($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $categories = AppCategory::join('app_category_translations','app_categories.id','app_category_translations.category_id')
                                        ->where('app_category_translations.language_code',$language)
                                        ->select(
                                            'app_categories.logo',
                                            'app_category_translations.category_id',
                                            'app_category_translations.language_code',
                                            'app_category_translations.name',
                                            'app_category_translations.desc',
                                        )
                                        ->orderBy('app_categories.created_at','DESC')
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

    public function getCategoriesByProductCount($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $categories = AppCategory::join('app_category_translations','app_categories.id','app_category_translations.category_id')
                                        ->join('app_products','app_categories.id','app_products.category_id')
                                        ->where('app_category_translations.language_code',$language)
                                        ->select(
                                            DB::raw("Count(*) as product_count"),
                                            'app_category_translations.name',
                                            'app_products.category_id',
                                        )
                                        ->groupBy(
                                            'app_category_translations.name',
                                            'app_products.category_id'
                                        )
                                        ->orderBy('app_categories.created_at','DESC')
                                        ->paginate(10);
            $data = array(
                'status_code' => '200',
                'message' => 'Category by product count has been fetched successfully!!',
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
    public function getProducts($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $productsQuery = AppProduct::join('app_product_translations','app_products.id','app_product_translations.product_id')
                                        ->join('app_product_specifications','app_products.id','app_product_specifications.product_id')
                                        ->where('app_product_translations.language_code',$language)
                                        ->where('app_product_specifications.language_code',$language);

            if($request->has('name')){
                $productsQuery = $productsQuery->where('app_product_translations.name','like','%'.$request->name.'%');
            }
            if($request->has('min_price')){
                $productsQuery->where('app_products.total_price','>=',$request->min_price);
            }
            if($request->has('max_price')){
                $productsQuery = $productsQuery->where('app_products.total_price','<=',$request->max_price);
            }
            if($request->has('categoory_id')){
                $productsQuery = $productsQuery->where('app_products.category_id',$request->category_id);
            }

            if($request->has('sort')){                
                if($request->sort == 'low-price'){
                    $productsQuery = $productsQuery->orderBy('app_products.total_price','ASC');
                }
                else if($request->sort == 'high-price'){
                    $productsQuery = $productsQuery->orderBy('app_products.total_price','DESC');
                }else{
                    $productsQuery = $productsQuery->orderBy('app_products.updated_at','DESC');
                }
            }

            $data = array(
                'status_code' => '200',
                'message' => 'All Product has been fetched successfully!!',
                'data' => $productsQuery->paginate(20)
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

    public function getProductDetails($request, $id)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $products = AppProduct::join('app_product_translations','app_products.id','app_product_translations.product_id')
                                        ->join('app_product_specifications','app_products.id','app_product_specifications.product_id')
                                        ->where('app_product_translations.language_code',$language)
                                        ->where('app_product_translations.language_code',$language)
                                        ->where('app_products.id',$id)
                                        ->first();
            $data = array(
                'status_code' => '200',
                'message' => 'Product details has been fetched successfully!!',
                'data' => $products
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
