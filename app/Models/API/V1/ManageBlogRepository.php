<?php

namespace App\Models\API\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\ErrorResource;

use App\Models\Blog;
use App\Models\BlogTranslation;

class ManageBlogRepository extends Model
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

    public function createBlog($request)
    {
        try{
            $blog = new Blog;
            $language  = isset($request->lang)?$request->lang:'en';

            if($request->has('cover_image')){
                $fileName = $request->file('cover_image')->getClientOriginalName();
                $path = $request->file('cover_image')->storeAs('blogs/'.time(), time().'_'.$fileName, 'public');
                $blog->cover_image = $path;
            }
            $blog->created_by = 1;
            $blog->updated_by = 1;
            $blog->deleted_by = 1;
            $blog->save();
            
            $blogTranslations = new BlogTranslation;
            $blogTranslations->blog_id = $blog->id;
            $blogTranslations->language_code = $language;
            $blogTranslations->title = $request->title;
            $blogTranslations->type = $request->type;
            $blogTranslations->desc = $request->desc;

            $blogTranslations->created_by = 1;
            $blogTranslations->updated_by = 1;
            $blogTranslations->deleted_by = 1;
            $blogTranslations->save();

            $data = array(
                'status_code' => '200',
                'message' => 'Blog has been created successfully!!',
                'data' => $blogTranslations
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

    public function getBlogById($request, $id)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $blog = Blog::join('blog_translations','blogs.id','blog_translations.blog_id')
                                        ->where('blog_translations.language_code',$language)
                                        ->where('blogs.id',$id)
                                        ->first();
            $data = array(
                'status_code' => '200',
                'message' => 'Blog details has been fetched successfully!!',
                'data' => $blog
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

    public function updateBlog($request)
    {
        try{
            $blog = Blog::findOrFail($id);

            $language  = isset($request->lang)?$request->lang:'en';

            if($request->has('cover_image')){
                $fileName = $request->file('cover_image')->getClientOriginalName();
                $path = $request->file('cover_image')->storeAs('blogs', time().'_'.$fileName, 'public');
                $blog->cover_image = $path;
            }
            $blog->created_by = 1;
            $blog->updated_by = 1;
            $blog->deleted_by = 1;
            $blog->save();
            
            $blogTranslation = BlogTranslation::where([
                ['blog_id',$blog->id],
                ['language_code', $language]
            ])->first();
            $messages = "Blog Details has been updated successfully!!";

            if(is_null($blogTranslation)){
                $blogTranslation = new BlogTranslation;
                $messages = "Blog ".$language." information added successfully!!";
            }

            $blogTranslation->blog_id = $blog->id;
            $blogTranslation->language_code = $language;
            $blogTranslation->title = $request->name;
            $blogTranslation->type = $request->type;
            $blogTranslation->desc = $request->desc;

            $blogTranslation->created_by = 1;
            $blogTranslation->updated_by = 1;
            $blogTranslation->deleted_by = 1;

            $blogTranslation->save();
            $data = array(
                'status_code' => '200',
                'message' => $messages,
                'data' => $blogTranslation
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

    public function deleteBlog($request)
    {
        try{
            $language  = isset($request->lang)?$request->lang:'en';
            $blog = Blog::findOrFail($request->id);
            if(is_null($blog)){
                $data = array(
                    'status_code' => '400',
                    'message' => 'Blog not Found',
                    'data' => []
                );
                return new ErrorResource($data);
            }
            $blogTranslation = BlogTranslation::where([
                ['blog_id',$blog->id],
                ['language_code', $language]
            ])->first();

            if(is_null($blogTranslation)){
                $data = array(
                    'status_code' => '400',
                    'message' => 'Blog details not found on '.$language.' language',
                    'data' => []
                );
                return new ErrorResource($data);
            }
            $blogTranslation->delete();
            $data = array(
                'status_code' => '200',
                'message' => 'Blog has been deleted successfully!!',
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

    public function uploadBlogImage($request)
    {
        try{
            $path = null;            
            if($request->has('image')){
                $fileName = $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->storeAs('blog-images/', time().'_'.$fileName, 'public');
            }

            $data = array(
                'status_code' => '200',
                'message' => 'Blog Image upload successfully!!',
                'data' => $path
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
