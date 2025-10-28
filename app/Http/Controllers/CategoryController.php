<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // dve glavne “kategorije” po tipu (rent/buy)
        $rentCount = Property::published()->whereHas('category', fn($q)=>$q->where('type','rent'))->count();
        $buyCount = Property::published()->whereHas('category', fn($q)=>$q->where('type','buy'))->count();

        $categories = Category::query()->orderBy('name')->get();
        return view('categories.rentCount', compact('rentCount','buyCount'));
    }

    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $properties = $category->properties()->published()->latest()->paginate(12);
        return view('categories.show', compact('category','properties'));
    }
}
