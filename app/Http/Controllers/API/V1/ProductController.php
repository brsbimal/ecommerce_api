<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProductRequest;

use App\Models\API\V1\ProductRepository;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->manageProduct = new ProductRepository();

    }

    public function getProducts(Request $request)
    {
        return $this->manageProduct->getProducts($request);
    }

    public function createProduct(CreateProductRequest $request)
    {
        return $this->manageProduct->createProduct($request);
    }

    public function getProductById(Request $request, $id){
        return $this->manageProduct->getProductById($request,$id);
    }

    public function updateProduct(CreateProductRequest $request,$id)
    {
        return $this->manageProduct->updateProduct($request,$id);
    }

    public function deleteProduct(Request $request, $id)
    {
        return $this->manageProduct->deleteProduct($request, $id);
    }
}
