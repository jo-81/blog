<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view("admin.category.list");
    }

    public function show(int $id)
    {
        return view("admin.category.show", [
            'category' => Category::findOrFail($id)
        ]);
    }
}
