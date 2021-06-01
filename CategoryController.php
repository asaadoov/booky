<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        if($request->ajax()) {
            $response ='
                <div class="col-sm-6 col-lg-4" >
                    <div data-toggle="modal" data-target="#modal" class="card p-3 bg-grey text-center"
                        style="min-height: 300px; cursor: pointer; font-size: 50px; padding-top: 100px !important;">
                        <i class="icon-plus"></i>
                        <div style="font-size: 20px; margin-top: 20px;" >Add New Category</div>
                    </div>
                </div>
            ';
            foreach($categories as $category)
            {
                $response = $response .'
                    <div class="col-sm-6 col-lg-4">
                        <div class="card p-3" style="height: 300px;">
                            <a href="javascript:void(0)" class="mb-3">
                                <img src="'.url($category->image_url).'" alt="" class="rounded"
                                style="height: 225px; width: 100%;">
                            </a>
                            <div class="align-items-center" id="category">
                                <span>'.$category->name.'</span>
                                <button type="button" class="btn btn-danger btn-sm pull-right"
                                        onclick="remove('.$category->id .')"><i class="fa fa-trash-o"></i></button>
                            </div>
                        </div>
                    </div>
                ';
            }
            
            return $response;
        };
        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validation
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:191',
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => $validator->errors()->first()
            ]);
        }

        // Upload file
        $path = $request->file('image')->store('public/categories');

        // Add category
        $category = Category::create([
            'name' => $request->name,
            'image_url' => \Storage::url($path),
        ]);

        return response()->json([
            'status' => 'success',
            'title' => 'Success',
            'message' => 'Data successfully added.'
        ]);
    }

    public function delete(Request $request)
    {
        $category = Category::findOrFail($request->id);

        \Storage::delete('public/categories/'.basename($category->image_url));
        $category->delete();

        return response()->json([
            'status' => 'success',
            'title' => 'Success',
            'message' => 'Data successfully deleted.'
        ]);
    }
}
