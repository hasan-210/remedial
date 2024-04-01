<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'status' => 'success',
            'posts' => $posts,
        ]);
    }

    public function store(Request $request)
    {

        $validator= Validator::make($request->all(), [
            'title'  => 'required|string|max:255',
            'description'  => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $post = new Post();
        $post->title=$request->title;
        $post->description=$request->description;
        $post->category_id=$request->category_id;

        // image
        if($request->file('image')){
            $file = $request->file('image');
            $filename = uniqid() . "_" . $file->getClientOriginalName();
            $file->move(public_path('images/post/'), $filename);
            $path = URL::to('/') . '/images/post/' . $filename;
            $post->image= $path ;
        }

        // $post->subcategory_id=$request->subcategory_id;

        $post->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'post' => $post,
        ]);

    }
    public function update(Request $request){
        $validator= Validator::make($request->all(), [
            'title'  => 'required|string|max:255',
            'description'  => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'post_id'     => 'required|exists:posts,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $post =Post::find($request->post_id);
        $post->title=$request->title;
        $post->description=$request->description;
        $post->category_id=$request->category_id;

        // image
        if($request->file('image')){
            // if($post->image){
            //     $oldPath = public_path($post->image);
            //     if (File::exists($oldPath)) {
            //         File::delete($oldPath); // Delete the old image from the previous path
            //     }
            // }
            $file = $request->file('image');
            $filename = uniqid() . "_" . $file->getClientOriginalName();
            $file->move(public_path('images/post/'), $filename);
            $path = URL::to('/') . '/images/post/' . $filename;
            $post->image= $path ;
        }

        // $post->subcategory_id=$request->subcategory_id;

        $post->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'post' => $post,
        ]);
    }
    public function delete(Request $request){
        $validator= Validator::make($request->all(), [
            'post_id'     => 'required|exists:posts,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::findOrFail($request->post_id) ;
            $post->delete();
            $message=[
                'title' =>"delete post",
            'body' =>"deleted successfully"];
            return response()->json($message,200);

    }
}
