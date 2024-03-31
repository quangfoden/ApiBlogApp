<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //
    public function create(Request $request)
    {
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->desc = $request->desc;

        if ($request->photo != '') {
            // chọn 1 unique name cho ảnh
            $photo = time() . 'jpg';
            file_put_contents('storage/posts/' . $photo, base64_decode($request->photo));
            $post->photo = $photo;
        }
        $post->save();
        $post->user;
        return response()->json(
            [
                'success' => true,
                'message' => "posted",
                'post' => $post
            ]
        );
    }

    public function update(Request $request)
    {
        $post = Post::find($request->id);
        if (Auth::user()->id != $request->id) {
            return response()->json([
                'success' => false,
                'message' => 'không có quyền edit'
            ]);
        }
        $post->desc = $request->desc;
        $post->update();
        return response()->json([
            'success' => true,
            'message' => 'post edited'
        ]);
    }
    public function delete(Request $request)
    {
        $post = Post::find($request->id);
        if (Auth::user()->id != $request->id) {
            return response()->json([
                'success' => false,
                'message' => 'không có quyền edit'
            ]);
        }
        if ($post->photo != '') {
            Storage::delete('puclic/posts/' . $post->photo);
        }
        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'post deleted'
        ]);
    }
    public function posts()
    {
        $posts = Post::orderBy('id', 'desc')->get();
        foreach ($posts as $post) {
            $post->user;
            $post['commentCount'] = count($post->comments);
            $post['likeCount'] = count($post->likes);
            $post['selfLike'] = false;
            foreach ($post->likes as $like) {
                if ($like->user_id == Auth::user()->id) {
                    $post['selfLike'] = true;
                }
            }
        }
        return response()->json([
            'success'=>true,
            'post'=>$post
        ]);
    }
}
