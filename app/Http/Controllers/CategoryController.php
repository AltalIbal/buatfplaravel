<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::latest()->get();

        return view('category.index',[
            'category' => $category
        ]);
    }

    public function create()
    {
        $action = 'add';

        return view('category.save', [
            'action' => $action,
        ]);
    }

    public function store(Request $request)
    {
        $insert = $request->all();

        Category::create($insert);

        return redirect()->route('categories.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Category $category)
    {
        $action = 'edit';

        return view('category.save',[
            'action' => $action,
            'row' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $update = $request->all();
        
        $category->update($update);
        
        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index');
    }

}
