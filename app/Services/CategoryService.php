<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;

class CategoryService
{

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getPaginate($perPage)
    {
        $categories = $this->categoryRepository->getPaginate($perPage);
        if ($categories) {
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Categories found',
                'data' => $categories
            ];
        } else {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Categories not found'
            ];
        }
    }

    public function getById($id)
    {
        $category = $this->categoryRepository->getById($id);
        if ($category) {
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Category found',
                'data' => $category
            ];
        } else {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Category not found'
            ];
        }
    }

    // save category
    public function saveCategory($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'slug' => 'required|string|between:2,100',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 400,
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $category = $this->categoryRepository->save($request);
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Category saved',
                'data' => $category
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Category not saved',
                'errors' => $e->getMessage()
            ];
        }
    }

    // update category
    public function updateCategory($id, $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'slug' => 'required|string|between:2,100',
        ]);

        if ($validator->fails()) {
            return [
                'code' => 400,
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ];
        }

        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Category not found',
                'errors' => [
                    'message' => 'Category not found'
                ]
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $category = $this->categoryRepository->update($id, $request);
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Category updated',
                'data' => $category
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Category not updated',
                'errors' => $e->getMessage()
            ];
        }
    }

    // delete category
    public function deleteCategory($id)
    {
        $category = $this->categoryRepository->getById($id);
        if (!$category) {
            return [
                'code' => 404,
                'status' => false,
                'message' => 'Category not found',
                'errors' => [
                    'message' => 'Category not found'
                ]
            ];
        }

        // begin transaction
        DB::beginTransaction();
        try {
            $this->categoryRepository->delete($id);
            DB::commit();
            return [
                'code' => 200,
                'status' => true,
                'message' => 'Category deleted',
                'data' => $category
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'code' => 500,
                'status' => false,
                'message' => 'Category not deleted',
                'errors' => $e->getMessage()
            ];
        }
    }
}
