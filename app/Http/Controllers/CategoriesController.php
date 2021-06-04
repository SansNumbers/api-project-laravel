<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;

use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::find($id);
    }


    public function getPosts($id)
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such category'
            ]);
        }
        return \DB::table('posts')->whereJsonContains('categories', array('value' => (int)$id))->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'title' => 'required|string|unique:categories,title',
            'description' => 'string'
        ]);

        $category = Category::create([
            'title' => $input['title'],
            'description' => $input['description']
        ]);

        return response()->json([
            'category' => $category,
            'status' => 'Success',
            'message' => 'Category created'
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $category = Category::find($id);

        if(!$category || $category['author'] != $user) {
            return response()->json([
                'status' => 'FAIL',
                'message'=> 'You have no access rights or category doesn`t exist'
            ]);
        } else {
            return $category->update($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $category = Category::find($id);

        if(!$category || $category['author'] != $user) {
            return response()->json([
                'status' => 'FAIL',
                'message'=>'You have no access rights or category doesn`t exist'
            ]);
        } else {
            return Category::destroy($id);
        }
    }
}
