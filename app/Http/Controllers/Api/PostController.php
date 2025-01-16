<?php

namespace App\Http\Controllers\Api;

use App\helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    //

    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->get();

        return ResponseHelper::success($posts, 'Posts retrieved successfully');
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'contents' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error('Validation failed', 422, $validator->errors());
        }

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->contents,
            'user_id' => Auth::id(),
        ]);

        return ResponseHelper::success($post, 'Post created successfully', 201);
    }

    /**
     * Display the specified post.
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return ResponseHelper::error('Post not found or access denied', 404);
        }

        return ResponseHelper::success($post, 'Post retrieved successfully');
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, $id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return ResponseHelper::error('Post not found or access denied', 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'contents' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error('Validation failed', 422, $validator->errors());
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->contents,
        ]);

        return ResponseHelper::success($post, 'Post updated successfully');
    }

    /**
     * Remove the specified post.
     */
    public function destroy($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$post) {
            return ResponseHelper::error('Post not found or access denied', 403);
        }

        $post->delete();

        return ResponseHelper::success(null, 'Post deleted successfully');
    }
}
