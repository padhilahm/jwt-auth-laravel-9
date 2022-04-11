<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        // only allow authenticated admin to access these methods
        $this->middleware('admin:api', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $categories = $this->categoryService->getPaginate(10);
        $response['code'] = $categories['code'];
        $response['message'] = $categories['message'];
        if ($categories['status']) {
            $response['data'] = $categories['data'];
        }
        return response()->json($response, $categories['code']);
    }

    public function store(Request $request)
    {
        $category = $this->categoryService->saveCategory($request);
        $response['code'] = $category['code'];
        $response['message'] = $category['message'];
        if ($category['status']) {
            $response['data'] = $category['data'];
        } else {
            $response['errors'] = $category['errors'];
        }
        return response()->json($response, $category['code']);
    }

    public function show($id)
    {
        $category = $this->categoryService->getById($id);
        $response['code'] = $category['code'];
        $response['message'] = $category['message'];
        if ($category['status']) {
            $response['data'] = $category['data'];
        }
        return response()->json($response, $category['code']);
    }

    public function update($id, Request $request, Category $category)
    {
        $category = $this->categoryService->updateCategory($id, $request);
        $response['code'] = $category['code'];
        $response['message'] = $category['message'];
        if ($category['status']) {
            $response['data'] = $category['data'];
        } else {
            $response['errors'] = $category['errors'];
        }
        return response()->json($response, $category['code']);
    }

    public function destroy($id)
    {
        $category = $this->categoryService->deleteCategory($id);
        $response['code'] = $category['code'];
        $response['message'] = $category['message'];
        if ($category['status']) {
            $response['data'] = $category['data'];
        } else {
            $response['errors'] = $category['errors'];
        }
        return response()->json($response, $category['code']);
    }
}
