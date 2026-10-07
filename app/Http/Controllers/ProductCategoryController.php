<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{


    public function index(){

        $categories = ProductCategory::all();

        return view("pages.productCat.categories",compact("categories"));

    }

    public function create(){

        return view("pages.productCat.add");

    }

    public function store(Request $request){

        $validated = $request->validate(['name' => 'required|string|unique:product_categories,name']);

        ProductCategory::create($validated);

        return redirect()->route('categories')->with('success','Category Added Successfully');

    }

    public function edit($id){

        $category = ProductCategory::findOrFail($id);

        return view('pages.productCat.edit',compact('category'));

    }

    public function update(Request $request, $id){

        $validated = $request->validate(['name'=> 'required|string|unique:product_categories,name']);

        ProductCategory::findOrFail($id)->update($validated);

        return redirect()->route('categories')->with('success','Category Updated Successfully');

    }

    public function delete($id){

        ProductCategory::findOrFail($id)->delete();
        return redirect()->route('categories')->with('success','Category Deleted');

    }


}
