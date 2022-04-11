<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    // get paginate latest
    public function getPaginate($perPage)
    {
        return $this->post->with('user', 'category')->latest()->paginate($perPage);
    }

    // get by id
    public function getById($id)
    {
        return $this->post->with('user', 'category')->find($id);
    }

    // update post
    public function update($id, $request)
    {
        $post = $this->post->find($id);
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        $post->user_id = auth()->user()->id;
        $post->save();

        return $post;
    }

    // save post
    public function save($request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        $post->user_id = auth()->user()->id;
        $post->save();

        return $post;
    }

    // get by slug
    public function getBySlug($slug)
    {
        return $this->post->with('user', 'category')->where('slug', $slug)->first();
    }

    // delete
    public function delete($id)
    {
        $post = $this->post->find($id);
        $post->delete();

        return $post;
    }
}
