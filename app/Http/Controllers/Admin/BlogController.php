<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BlogCommentDataTable;
use App\DataTables\BlogDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCreateRequet;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Traits\FileUploadTrait;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Str;

class BlogController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(BlogDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.blog.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blog.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCreateRequet $request)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Create & save blog first
            $blog = new Blog();
            $blog->user_id = Auth::id();
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title);
            $blog->category_id = $request->category;
            $blog->description = $request->description;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $blog->status = $request->status;
            $blog->save();

            $imagePath = $this->uploadImage(
                $request,
                'image',
                $blog,
                'blogs'
            );

            $blog->image = $imagePath;
            $blog->save();

            DB::commit();

            toastr()->success('Created Successfully');
            return redirect()->route('admin.blogs.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove uploaded media if needed
            if (isset($blog) && $blog->hasMedia('blogs')) {
                $this->removeImage($blog);
            }

            \Log::error('Blog creation failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to create blog');
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        $blogCategories = BlogCategory::all();
        return view('admin.blog.edit', compact('blog', 'blogCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogUpdateRequest $request, string $id)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Find the blog
            $blog = Blog::findOrFail($id);

            // 2️⃣ Update blog fields
            $blog->user_id = Auth::id();
            $blog->title = $request->title;
            $blog->slug = Str::slug($request->title);
            $blog->category_id = $request->category;
            $blog->description = $request->description;
            $blog->seo_title = $request->seo_title;
            $blog->seo_description = $request->seo_description;
            $blog->status = $request->status;

            $imagePath = $this->uploadImage(
                $request,
                'image',
                $blog,
            );

            $blog->image = !empty($imagePath) ? $imagePath : $request->old_image;

            $blog->save();

            DB::commit();

            toastr()->success('Updated Successfully');
            return redirect()->route('admin.blogs.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove newly uploaded media if needed
            if (isset($blog) && $blog->hasMedia('blogs')) {
                $this->removeImage($blog);
            }

            \Log::error('Blog update failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to update blog');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $slider = Blog::findOrFail($id);
            $slider->delete();
            $this->removeImage(
                $slider,
                "blogs",
                // $slider->image
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function blogComment(BlogCommentDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.blog.comment.index');
    }


    public function commentStatusUpdate(string $id): RedirectResponse
    {
        $comment = BlogComment::find($id);

        //? change status of comment to active or inactive
        $comment->status = !$comment->status;
        $comment->save();

        toastr()->success('Updated Successfully');
        return redirect()->back();
    }


    public function commentDestroy(string $id): JsonResponse
    {
        try {
            $comment = BlogComment::findOrFail($id);
            $comment->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
