<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Validator;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPostPaginate($perPage)
    {
        $posts = $this->postRepository->getPaginate($perPage);
        if ($posts) {
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Posts found',
                'data' => $posts
            ];
        } else {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Posts not found'
            ];
        }
    }

    public function getPostById($id)
    {
        $post = $this->postRepository->getById($id);
        if ($post) {
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Post found',
                'data' => $post
            ];
        } else {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Post not found'
            ];
        }
    }

    public function updatePost($id, $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,100',
            'slug' => 'required|string|between:2,1000|unique:posts,slug,' . $id,
            'content' => 'required|string|between:2,1000',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 422,
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ];
        }

        $post = $this->postRepository->getById($id);
        if (!$post) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Post not found',
                'errors' => [
                    'message' => 'Post not found'
                ]
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $post = $this->postRepository->update($id, $request);
            // commit transaction
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Post updated',
                'data' => $post
            ];
        } catch (Exception $e) {
            // rollback transaction
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Post not updated',
                'errors' => [
                    'message' => $e->getMessage()
                ]
            ];
        }
    }

    public function savePost($request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|between:2,100',
            'slug' => 'required|string|between:2,1000|unique:posts',
            'content' => 'required|string|between:2,1000',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 422,
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $post = $this->postRepository->save($request);
            // commit transaction
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Success to create post',
                'data' => $post
            ];
        } catch (Exception $e) {
            // rollback transaction
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Failed to save post',
                'errors' => [
                    'message' => $e->getMessage()
                ]
            ];
        }
    }

    public function getPostBySlug($slug)
    {
        $post = $this->postRepository->getBySlug($slug);
        if ($post) {
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Post found',
                'data' => $post
            ];
        } else {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Post not found'
            ];
        }
    }

    public function deletePost($id)
    {
        $post = $this->postRepository->getById($id);
        if (!$post) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Post not found',
                'errors' => [
                    'message' => 'Post not found'
                ]
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $post = $this->postRepository->delete($id);
            // commit transaction
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Success to delete post',
                'data' => $post
            ];
        } catch (Exception $e) {
            // rollback transaction
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Failed to delete post',
                'errors' => [
                    'message' => $e->getMessage()
                ]
            ];
        }
    }
}
