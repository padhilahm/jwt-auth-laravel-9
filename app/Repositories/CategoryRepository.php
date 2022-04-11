<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getPaginate($perPage)
    {
        return $this->category->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return $this->category->find($id);
    }

    public function update($id, $request)
    {
        $category = $this->category->find($id);
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();

        return $category;
    }

    public function save($request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();

        return $category;
    }

    public function delete($id)
    {
        $category = $this->category->find($id);
        $category->delete();
    }
}
