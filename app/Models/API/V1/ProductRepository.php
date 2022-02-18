<?php

namespace App\Models\API\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;

use App\Models\AppProduct;
use App\Models\AppProductTranslation;
use App\Models\AppProductSpecification;
use Storage;

class ProductRepository extends Model
{
    public function getProducts($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $products = AppProduct::join('app_product_translations','app_products.id','app_product_translations.product_id')
                                        ->join('app_product_specifications','app_products.id','app_product_specifications.product_id')
                                        ->where('app_product_translations.language_code',$language)
                                        ->where('app_product_specifications.language_code',$language)
                                        ->paginate(10);
            $data = array(
                'status_code' => '200',
                'message' => 'All Product has been fetched successfully!!',
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

    public function createProduct($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $appProduct = new AppProduct;
            
            $appProduct->category_id = $request->category_id;
            $appProduct->price = $request->price;
            $appProduct->vat = $request->vat;
            $total_price = $request->price + $request->price*($request->vat/100);
            $appProduct->total_price = $total_price;

            if($request->has('img1')){
                $fileName = $request->file('img1')->getClientOriginalName();
                $path = $request->file('img1')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img1 = $path;
            }
            if($request->has('img2')){
                $fileName = $request->file('img2')->getClientOriginalName();
                $path = $request->file('img2')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img2 = $path;
            }
            if($request->has('img3')){
                $fileName = $request->file('img3')->getClientOriginalName();
                $path = $request->file('img3')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img3 = $path;
            }
            if($request->has('img4')){
                $fileName = $request->file('img4')->getClientOriginalName();
                $path = $request->file('img4')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img4 = $path;
            }

            $appProduct->created_by = 1;
            $appProduct->updated_by = 1;
            $appProduct->deleted_by = 1;
            $appProduct->is_active = $request->is_active;
            $appProduct->is_new = $request->is_new;
            $appProduct->is_featured = $request->is_featured;
            $appProduct->save();

            $appProductTranslation = new AppProductTranslation;
            $appProductTranslation->product_id = $appProduct->id;
            $appProductTranslation->language_code = $language;
            $appProductTranslation->name = $request->name;
            $appProductTranslation->short_desc = $request->short_desc;
            $appProductTranslation->desc = $request->desc;
            $appProductTranslation->created_by = 1;
            $appProductTranslation->updated_by = 1;
            $appProductTranslation->deleted_by = 1;
            $appProductTranslation->save();

            $appProductSpecification = new AppProductSpecification;

            $appProductSpecification->product_id = $appProduct->id;

            $appProductSpecification->language_code = $language;

            $appProductSpecification->brand = $request->brand;

            $appProductSpecification->made_type = $request->made_type;

            $appProductSpecification->materials = $request->materials;

            $appProductSpecification->weight = $request->weight;
            $appProductSpecification->weight_unit = $request->weight_unit;

            $appProductSpecification->dimension = $request->dimension;
            $appProductSpecification->dimension_unit = $request->dimension_unit;

            $appProductSpecification->categories = $request->categories;

            $appProductSpecification->size = $request->size;
            $appProductSpecification->size_unit = $request->size_unit;
            $appProductSpecification->size_remarks = $request->size_remarks;

            $appProductSpecification->color = $request->color;
            $appProductSpecification->color_remarks = $request->color_remarks;
            $appProductSpecification->save();

            $data = array(
                'status_code' => '200',
                'message' => 'Product has been added successfully!!',
                'data' => $appProductTranslation
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

    public function getProductById($request, $id)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $products = AppProduct::join('app_product_translations','app_products.id','app_product_translations.product_id')
                                        ->join('app_product_specifications','app_products.id','app_product_specifications.product_id')
                                        ->where('app_product_translations.language_code',$language)
                                        ->where('app_product_specifications.language_code',$language)
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

    public function updateProduct($request,$id)
    {
        try{
            $appProduct = AppProduct::findOrFail($id);
            $language  = isset($request->lang)?$request->lang:'en';

            $appProduct->category_id = $request->category_id;
            $appProduct->price = $request->price;
            $appProduct->vat = $request->vat;
            $total_price = $request->price + $request->price*($request->vat/100);
            $appProduct->total_price = $total_price;

            if($request->has('img1')){
                $fileName = $request->file('img1')->getClientOriginalName();
                $path = $request->file('img1')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img1 = $path;
            }
            if($request->has('img2')){
                $fileName = $request->file('img2')->getClientOriginalName();
                $path = $request->file('img2')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img2 = $path;
            }
            if($request->has('img3')){
                $fileName = $request->file('img3')->getClientOriginalName();
                $path = $request->file('img3')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img3 = $path;
            }
            if($request->has('img4')){
                $fileName = $request->file('img4')->getClientOriginalName();
                $path = $request->file('img4')->storeAs('products', time().'_'.$fileName, 'public');
                $appProduct->img4 = $path;
            }

            $appProduct->created_by = 1;
            $appProduct->updated_by = 1;
            $appProduct->deleted_by = 1;
            $appProduct->is_active = $request->is_active;
            $appProduct->is_new = $request->is_new;
            $appProduct->is_featured = $request->is_featured;
            $appProduct->save();

            $appProductTranslation = AppProductTranslation::where([
                ['product_id',$appProduct->id],
                ['language_code', $language]
            ])->first();
            $messages = "Product Details has been updated successfully!!";

            if(is_null($appProductTranslation)){
                $appProductTranslation = new AppProductTranslation;
                $messages = "Product ".$language." information added successfully!!";
            }

            $appProductTranslation->product_id = $appProduct->id;
            $appProductTranslation->language_code = $language;
            $appProductTranslation->name = $request->name;
            $appProductTranslation->short_desc = $request->short_desc;
            $appProductTranslation->desc = $request->desc;
            $appProductTranslation->created_by = 1;
            $appProductTranslation->updated_by = 1;
            $appProductTranslation->deleted_by = 1;
            $appProductTranslation->save();

            $appProductSpecification = AppProductSpecification::where([
                ['product_id',$appProduct->id],
                ['language_code', $language]
            ])->first();

            $messages = "Product Specification has been updated successfully!!";

            if(is_null($appProductSpecification)){
                $appProductSpecification = new AppProductSpecification;
                $messages = "Product ".$language." Specification information added successfully!!";
            }

            $appProductSpecification->product_id = $appProduct->id;

            $appProductSpecification->language_code = $language;

            $appProductSpecification->brand = $request->brand;

            $appProductSpecification->made_type = $request->made_type;

            $appProductSpecification->materials = $request->materials;

            $appProductSpecification->weight = $request->weight;
            $appProductSpecification->weight_unit = $request->weight_unit;

            $appProductSpecification->dimension = $request->dimension;
            $appProductSpecification->dimension_unit = $request->dimension_unit;

            $appProductSpecification->categories = $request->categories;

            $appProductSpecification->size = $request->size;
            $appProductSpecification->size_unit = $request->size_unit;
            $appProductSpecification->size_remarks = $request->size_remarks;

            $appProductSpecification->color = $request->color;
            $appProductSpecification->color_remarks = $request->color_remarks;
            $appProductSpecification->save();


            $data = array(
                'status_code' => '200',
                'message' => $messages,
                'data' => $appProductTranslation
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

    public function deleteProduct($request,$id)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $appProduct = AppProduct::where('id',$id)->first();
            if(is_null($appProduct)){
                $data = array(
                    'status_code' => '400',
                    'message' => 'Product not Found',
                    'data' => []
                );
                return new ErrorResource($data);
            }
            $appProductTranslation = AppProductTranslation::where([
                ['product_id',$appProduct->id],
                ['language_code', $language]
            ])->first();

            $appProductSpecification = AppProductSpecification::where([
                ['product_id',$appProduct->id],
                ['language_code', $language]
            ])->first();

            if(is_null($appProductTranslation)){
                $data = array(
                    'status_code' => '400',
                    'message' => 'Product details not found on '.$language.' language',
                    'data' => []
                );
                return new ErrorResource($data);
            }
            if(is_null($appProductSpecification)){
                $data = array(
                    'status_code' => '400',
                    'message' => 'Product Specification details not found on '.$language.' language',
                    'data' => []
                );
                return new ErrorResource($data);
            }
            $appProductTranslation->delete();
            $appProductSpecification->delete();

            $data = array(
                'status_code' => '200',
                'message' => 'Product has been deleted successfully!!',
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
