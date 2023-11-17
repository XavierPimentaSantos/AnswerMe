<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $post = new Post();

        $this->authorize('create', $post);

        $post->user_id = Auth::user()->id;

        $post->save();
    }

    public function delete(Request $request, $id)
    {
        $post = Post::find($id);
    }
}
