<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;

use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function show($id)
    {
        return Comment::where('id', $id)->get();
    }

    public function getLikesComment($id)
    {
        return Like::where('comment_id', (int)$id)->get();
    }

    public function likeComment(Request $request, $id) {
    {
        $credentials = $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        if (empty(Comment::find($id))) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such post'
            ]);
        }

        $comment = Comment::find($id);
        $like = Like::where('author', $credentials['type'])->where('comment_id', $id)->get();

        if (count($like) > 0) {
            if ($credentials['type'] === 'like') { // client sent like
                if ($like[0]['type'] === 'like')
                    return $this->unlike($request, $id); // delete like
                else { // change dislike on like
                    // Update rating of post and type of like entity
                    $comment->rating += 1;
                    $comment->save();
                    $like[0]->type = 'like';
                    $like[0]->save();
                    $user = User::find($comment->author);
                    $user->rating += 1;
                    $user->save();
                    return $like[0];
                }
            }
            else { // client sent dislike
                if ($like[0]['type'] === 'dislike')
                    return $this->unlike($request, $id); // delete dislike
                else { // change like on dislike
                    // Update rating of post and type of like entity
                    $comment->rating -= 1;
                    $comment->save();
                    $like[0]->type = 'dislike';
                    $like[0]->save();
                    $user = User::find($comment->author);
                    $user->rating -= 1;
                    $user->save();
                    return $like[0];
                }
            }
        }

        $credentials['comment_id'] = $id;
        $credentials['author'] = auth()->user()->id;

        $user = User::find($comment->author);
        if ($credentials['type'] === 'like') {
            $comment->rating++;
            $comment->rating++;
        }
        else {
            $comment->rating--;
            $comment->rating--;
        }
        $comment->save();

        return Like::create($credentials);
        }
    }

    public function removeLikeComment(Request $request, $id) {

        if (empty(Comment::find($id))) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such post'
            ]);
        }

        $request = array('author' => auth()->user()->id);
        $request['comment_id'] = $id;

        $like = Like::where('author', $request['author'])->where('comment_id', $id);
        if (count($like->get()) === 0)
            return 0;

        $comment = Comment::find($id);
        $user = User::find($comment->author);
        if (($like->get())[0]['type'] === 'like') {
            $comment->rating--;
            $user->rating--;
        }
        else {
            $comment->rating++;
            $user->rating++;
        }
        $comment->save();
        $user->save();

        return $like->delete();
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $comment = Comment::find($id);

        if(!$comment || $comment['author'] != $user){
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You have no access rights or comment doesn`t exist'
            ]);
        } else {
            $comment->update($request->all());
            return $comment;
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $comment = Comment::find($id);

        if(!$comment || $comment['author'] != $user) {
            return response()->json([
                'status' => 'FAIL',
                'message'=>'You have no access rights or post doesn`t exists'
            ]);
        } else {
            return Comment::destroy($id);
        }
    }
}
