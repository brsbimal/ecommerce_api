<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\API\V1\ManageFrontend;

class FrontendController extends Controller
{
    public function __construct()
    {
        $this->manageFrontend = new ManageFrontend();
    }

    public function getBlogs(Request $request)
    {
        return $this->manageFrontend->getBlogs($request);

    }
}
