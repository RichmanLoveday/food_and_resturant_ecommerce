<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ChefDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChefCreateRequest;
use App\Http\Requests\Admin\ChefUpdateRequest;
use App\Models\Chefs;
use App\Models\SectionTitle;
use App\Traits\FileUploadTrait;
use App\Traits\SectionTitlesTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChefController extends Controller
{
    use FileUploadTrait;
    use SectionTitlesTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(ChefDataTable $dataTable): View|JsonResponse
    {
        $key = [
            'chefs_top_title',
            'chefs_main_title',
            'chefs_sub_title'
        ];
        $titles = $this->getSectionTitles($key);

        return $dataTable->render('admin.chef.index', compact('titles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.chef.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChefCreateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Create chef
            $chef = new Chefs();
            $chef->name = $request->name;
            $chef->title = $request->title;
            $chef->fb = $request->fb;
            $chef->in = $request->in;
            $chef->x = $request->x;
            $chef->web = $request->web;
            $chef->show_at_home = $request->show_at_home ? 1 : 0;
            $chef->status = $request->status ? 1 : 0;

            // 2️⃣ Save first (required for media handling)
            $chef->save();

            // 3️⃣ Upload image if provided
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $chef,
                    'chefs'
                );

                if (!is_null($imagePath)) {
                    $chef->image = $imagePath;
                    $chef->save();
                }
            }

            DB::commit();

            toastr()->success('Created Successfully');
            return redirect()->route('admin.chef.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove newly uploaded media if needed
            if (isset($chef) && method_exists($chef, 'clearMediaCollection')) {
                $chef->clearMediaCollection('chefs');
            }

            \Log::error('Chef creation failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to create Chef');
            return redirect()->back()->withInput();
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
        $chef = Chefs::findOrFail($id);
        return view('admin.chef.edit', compact('chef'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChefUpdateRequest $request, string $id): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Find chef
            $chef = Chefs::findOrFail($id);
            $chef->name = $request->name;
            $chef->title = $request->title;
            $chef->fb = $request->fb;
            $chef->in = $request->in;
            $chef->x = $request->x;
            $chef->web = $request->web;
            $chef->show_at_home = $request->show_at_home ? 1 : 0;
            $chef->status = $request->status ? 1 : 0;

            // 2️⃣ Upload new image if provided
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $chef,
                    'chefs'
                );

                if (!is_null($imagePath)) {
                    $chef->image = $imagePath;
                }
            }

            // 3️⃣ Save updates
            $chef->save();

            DB::commit();

            toastr()->success('Updated Successfully!');
            return redirect()->route('admin.chef.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove newly uploaded media if needed
            if (isset($chef) && method_exists($chef, 'clearMediaCollection')) {
                $chef->clearMediaCollection('chefs');
            }

            \Log::error('Chef update failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to update Chef');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Update the title for the chef section.
     * This method updates the section titles for the daily offer page.
     */
    public function updateTitle(Request $request)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'chefs_top_title' => 'max:255',
            'chefs_main_title' => 'max:255',
            'chefs_sub_title' => 'max:255',
        ]);

        foreach ($validatedData as $key => $value) {
            //? update or create the section title for daily offer
            SectionTitle::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        //? flash message
        toastr()->success('Updated successfully');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $chef = Chefs::findOrFail($id);

            // Optional: remove associated image first
            $this->removeImage($chef, 'chefs');

            // Delete chef
            $chef->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully!',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Chef deletion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}