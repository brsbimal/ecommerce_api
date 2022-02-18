<?php

namespace App\Models\API\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;

use App\Models\Blog;

class ManageFrontend extends Model
{
    public function getBlogs($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $categories = Blog::join('blog_translations','blogs.id','blog_translations.blog_id')
                                        ->where('blog_translations.language_code',$language)
                                        ->orderBy('blogs.created_at','DESC')
                                        ->paginate(20);
            $data = array(
                'status_code' => '200',
                'message' => 'All Blogs has been fetched successfully!!',
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
}
