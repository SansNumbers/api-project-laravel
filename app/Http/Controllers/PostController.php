<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    //Show all posts (for all)
    public function index()
    {
        return Post::all();
    }

    //Show specified comment under post (for all)
    public function getComment($id)
    {
        return Comment::where('post_id', $id)->get();
    }

    //Show specified post (for all)
    public function show($id)
    {
        return Post::find($id);
    }

    //Create comment
    public function createComment(Request $request, $id)
    {
        $credentials = $request->validate([
            'content' => 'required|string'
        ]);

        $post = Post::where('id', $id)->first();

        $comment = Comment::create([
            'content' => $credentials['content'],
            'author' => Auth::user()->id,
            'post_id' => $post['id']
        ]);

        if(!$post) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such post'
            ]);
        }

        if(!$comment) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'Error'
            ]);
        } else {
            return response()->json([
                'status' => 'Success',
                'message' => 'Comment has been created'
            ]);
        }
    }

    //Create post
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'categories' => 'required|string'
        ]);

        $category = Category::where('title', $credentials['categories'])->first();

        $post = Post::create([
            'title' => $credentials['title'],
            'content' => $credentials['content'],
            'categories' => $credentials['categories'],
            'author' => Auth::user()->id
        ]);

        //if we have no category
        if(!$category) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such category'
            ]);
        }

        if(!$post) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'Error'
            ]);
        } else {

            return response()->json([
                'status' => 'Success',
                'message' => 'Post has been created'
            ]);
        }
    }

    //Get all likes
    public function getLikes($id) {
        return Like::where('post_id', (int)$id)->get();
    }

    //Like post
    public function like(Request $request, $id) {
    {
        $credentials = $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        if (empty(Post::find($id))) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such post'
            ]);
        }

        $post = Post::find($id);
        $like = Like::where('author', $credentials['type'])->where('post_id', $id)->get();

        if (count($like) > 0) {
            if ($credentials['type'] === 'like') {
                if ($like[0]['type'] === 'like')
                    return $this->unlike($request, $id);
                else {
                    $post->rating += 1;
                    $post->save();
                    $like[0]->type = 'like';
                    $like[0]->save();
                    $user = User::find($post->author);
                    $user->rating += 1;
                    $user->save();
                    return $like[0];
                }
            }
            else { // client sent dislike
                if ($like[0]['type'] === 'dislike')
                    return $this->unlike($request, $id);
                else {
                    $post->rating -= 1;
                    $post->save();
                    $like[0]->type = 'dislike';
                    $like[0]->save();
                    $user = User::find($post->author);
                    $user->rating -= 1;
                    $user->save();
                    return $like[0];
                }
            }
        }

        $credentials['post_id'] = $id;
        $credentials['author'] = auth()->user()->id;

        $user = User::find($post->author);
        if ($credentials['type'] === 'like') {
            $post->rating++;
            $post->rating++;
        }
        else {
            $post->rating--;
            $post->rating--;
        }
        $post->save();

        return Like::create($credentials);
        }
    }

    //Remove like
    public function removeLike(Request $request, $id) {

        if (empty(Post::find($id))) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such post'
            ]);
        }

        $request = array('author' => auth()->user()->id);
        $request['post_id'] = $id;

        $like = Like::where('author', $request['author'])->where('post_id', $id);
        if (count($like->get()) === 0)
            return 0;

        $post = Post::find($id);
        $user = User::find($post->author);
        if (($like->get())[0]['type'] === 'like') {
            $post->rating--;
            $user->rating--;
        }
        else {
            $post->rating++;
            $user->rating++;
        }
        $post->save();
        $user->save();

        return $like->delete();
    }

    //Update post
    public function update(Request $request, $id)
    {
        $user = $this->isAdmin();
        
        $posts = Post::find($id);

        if(!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message'=> 'You have no access rights'
            ]);
        } else {
            $posts->update($request->all());
            return $posts;
        }

        $user = User::findOrFail(auth()->user()->id)['login'];

        $posts = Post::find($id);

        if(!$posts || $posts['author'] != $user || $user->role != 'admin'){
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You have no access rights or post doesn`t exist'
            ]);
        } else {
            $posts->update($request->all());
            return $posts;
        }
    }

    //Delete post
    public function destroy($id)
    {
        $user = $this->isAdmin();

        if(!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message'=> 'You have no access rights'
            ]);
        } else {
            return Post::destroy($id);
        }

        $user = User::findOrFail(auth()->user()->id)['login'];
        $post = Post::find($id);

        if(!$post || $post['author'] != $user || $user->role != 'admin') {
            return response()->json([
                'status' => 'FAIL',
                'message'=> 'You have no access rights or post doesn`t exists'
            ]);
        } else {
            return Post::destroy($id);
        }
    }
}
