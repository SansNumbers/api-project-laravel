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

    //All categories (admin only)
    public function index()
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

        return Category::all();
    }

    //Display specified category (admin only)
    public function show($id)
    {
        $user = $this->isAdmin();
        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }
        return Category::find($id);
    }

    //Get posts by categories 
    public function getPosts($id)
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

        $category = Category::find($id);
        if (empty($category)) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no such category'
            ]);
        }
        return \DB::table('posts')->whereJsonContains('categories', array('value' => (int)$id))->get();
    }

    //Create new category (admin only)
    public function store(Request $request)
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

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

    //Update category (admin only)
    public function update(Request $request, $id)
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

        $user = User::findOrFail(auth()->user()->id)['login'];
        $category = Category::find($id);

        return $category->update($request->all());
    }

    //Delete category (admin only)
    public function destroy($id)
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

        return Category::destroy($id);
    }
}
