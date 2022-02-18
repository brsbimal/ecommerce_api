<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\V1\ManageShopRepository;
// use App\Models\API\V1\ProductRepository;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->manageShopRepository = new ManageShopRepository();
    }

    public function getCategories(Request $request)
    {
        return $this->manageShopRepository->getCategories($request);
    }

    public function getCategoriesByProductCount(Request $request)
    {
        return $this->manageShopRepository->getCategoriesByProductCount($request);
    }

    public function getProducts(Request $request)
    {
        return $this->manageShopRepository->getProducts($request);
    }

    public function getProductDetails(Request $request,$id)
    {
        return $this->manageShopRepository->getProductDetails($request,$id);
    }
}
