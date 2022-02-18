<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UploadBlogImageRequest;

use App\Models\API\V1\ManageBlogRepository;

class AdminBlogController extends Controller
{

    public function __construct()
    {
        $this->manageBlogRepository = new ManageBlogRepository();
    }

    public function getBlogs(Request $request)
    {
        return $this->manageBlogRepository->getBlogs($request);

    }
    public function createBlog(CreateBlogRequest $request)
    {
        return $this->manageBlogRepository->createBlog($request);
        
    }
    
    public function getBlogById(Request $request, $id)
    {
        return $this->manageBlogRepository->getBlogById($request, $id);
    }

    public function updateBlog(CreateBlogRequest $request)
    {
        return $this->manageBlogRepository->updateBlog($request);
    }

    Public function deleteBlog(Request $request)
    {
        return $this->manageBlogRepository->deleteBlog($request);
    }

    public function uploadBlogImage(UploadBlogImageRequest $request)
    {
        return $this->manageBlogRepository->uploadBlogImage($request);
    }

}
