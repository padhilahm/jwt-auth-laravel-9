<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PostService;

class PostController extends Controller
{
    protected $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware('auth:api', ['except' => ['index', 'show', 'showBySlug']]);
    }

    public function index()
    {
        $posts = $this->postService->getPostPaginate(10);
        $response['code'] = $posts['code'];
        $response['message'] = $posts['message'];

        if ($posts['status']) {
            $response['data'] = $posts['data'];
        }

        return response()->json($response, $posts['code']);
    }

    public function store(Request $request)
    {
        $post = $this->postService->savePost($request);
        $response['code'] = $post['code'];
        $response['message'] = $post['message'];

        if ($post['status']) {
            $response['data'] = $post['data'];
        } else {
            $response['errors'] = $post['errors'];
        }

        return response()->json($response, $post['code']);
    }

    public function show($id)
    {
        $post = $this->postService->getPostById($id);
        $response['code'] = $post['code'];
        $response['message'] = $post['message'];

        if ($post['status']) {
            $response['data'] = $post['data'];
        }

        return response()->json($response, $post['code']);
    }

    public function update($id, Request $request)
    {
        $post = $this->postService->updatePost($id, $request);
        $response['code'] = $post['code'];
        $response['message'] = $post['message'];

        if ($post['status']) {
            $response['data'] = $post['data'];
        } else {
            $response['errors'] = $post['errors'];
        }

        return response()->json($response, $post['code']);
    }

    public function destroy($id)
    {
        $post = $this->postService->deletePost($id);
        $response['code'] = $post['code'];
        $response['message'] = $post['message'];

        if ($post['status']) {
            $response['data'] = $post['data'];
        } else {
            $response['errors'] = $post['errors'];
        }

        return response()->json($response, $post['code']);
    }

    // show by slug
    public function showBySlug($slug)
    {
        $post = $this->postService->getPostBySlug($slug);
        $response['code'] = $post['code'];
        $response['message'] = $post['message'];
        if ($post['status']) {
            $response['data'] = $post['data'];
        }

        return response()->json($response, $post['code']);
    }
}
