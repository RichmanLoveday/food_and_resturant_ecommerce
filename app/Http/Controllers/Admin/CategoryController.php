<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminCategoryCreateRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.product.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.product.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminCategoryCreateRequest $request): RedirectResponse
    {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;
        $category->show_at_home = $request->show_at_home;
        $category->save();

        //? flash succes message
        toastr()->success('Created Successfullly');

        return redirect()->route('admin.category.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $category = Category::findOrFail($id);
        return view('admin.product.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, string $id)
    {
        $category = Category::findOrFail($id);

        //? update why choose us data
        $category->update($request->validated());

        //? flash message
        toastr()->success('Updated Successfully');

        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong'], 200);
        }
    }
}