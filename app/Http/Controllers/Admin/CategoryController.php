<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware(['role:admin']);
    }

    public function index(Request $request)
    {
        $searchCategory = ($request->searchCategory) ? $request->searchCategory : '';

        $categories = Category::where(function($q) use ($searchCategory) {
                if ($searchCategory) {
                    $q->where('name', 'LIKE', '%'.$searchCategory.'%');
                }
            })->paginate(20);

        return view('admin.categories.index', compact('categories', 'searchCategory'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories'
        ]);

        Category::create($request->all());

        return redirect()->back()->with('message', 'Categoría creada correctamente');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));        
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,id,'.$category->id
        ]);

        $category->update($request->all());

        return redirect()->back()->with('message', 'Categoría actualizada correctamente');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->back()->with('message', 'Categoría eliminada correctamente');
    }
}
