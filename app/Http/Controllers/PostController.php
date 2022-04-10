<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct()
    {
        // only allow authenticated admin to access these methods
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user', 'category')->latest()->paginate(5);
        return response()->json([
            'message' => 'success',
            'data' => $posts
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,100',
            'slug' => 'required|string|between:2,1000',
            'content' => 'required|string|between:2,1000',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Created Successfully',
            'data' => $post
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('user', 'category')->find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Post found',
            'data' => $post
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,100',
            'slug' => 'required|string|between:2,1000',
            'content' => 'required|string|between:2,1000',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        $post->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Updated Successfully',
            'data' => $post
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'message' => 'Deleted Successfully',
            'data' => $post
        ], 200);
    }

    // show by slug
    public function showBySlug($slug)
    {
        $post = Post::with('user', 'category')->where('slug', $slug)->first();
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Post found',
            'data' => $post
        ], 200);
    }
}
