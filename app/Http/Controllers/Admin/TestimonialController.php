<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TestimonialDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialCreateRequest;
use App\Http\Requests\Admin\TestimonialUpdateRequest;
use App\Models\SectionTitle;
use App\Models\Testimonial;
use App\Traits\FileUploadTrait;
use App\Traits\SectionTitlesTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    use FileUploadTrait;
    use SectionTitlesTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(TestimonialDataTable $dataTable): View|JsonResponse
    {
        $key = [
            'testimonial_top_title',
            'testimonial_main_title',
            'testimonial_sub_title'
        ];
        $titles = $this->getSectionTitles($key);

        return $dataTable->render('admin.testimonial.index', compact('titles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(TestimonialCreateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Create testimonial first
            $testimonial = new Testimonial();
            $testimonial->name = $request->name;
            $testimonial->title = $request->title;
            $testimonial->rating = $request->rating;
            $testimonial->review = $request->review;
            $testimonial->show_at_home = $request->show_at_home;
            $testimonial->status = $request->status;
            $testimonial->save(); // save first for uploadImage

            // 2️⃣ Upload image after saving
            $imagePath = $this->uploadImage(
                $request,
                'image',
                $testimonial,
                'testimonials'
            );

            if (!is_null($imagePath)) {
                $testimonial->image = $imagePath;
                $testimonial->save();
            }

            DB::commit();

            toastr()->success("Created Successfully");
            return redirect()->route('admin.testimonial.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Testimonial creation failed', [
                'error' => $e->getMessage(),
            ]);

            toastr()->error('Something went wrong while creating testimonial');
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
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TestimonialUpdateRequest $request, string $id): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Fetch testimonial
            $testimonial = Testimonial::findOrFail($id);

            // 2️⃣ Upload image if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $testimonial,
                    'testimonials'
                );
            }

            // 3️⃣ Update fields
            $testimonial->image = $imagePath ?? $testimonial->image;
            $testimonial->name = $request->name;
            $testimonial->title = $request->title;
            $testimonial->rating = $request->rating;
            $testimonial->review = $request->review;
            $testimonial->show_at_home = $request->show_at_home;
            $testimonial->status = $request->status;
            $testimonial->save();

            DB::commit();

            toastr()->success("Updated Successfully");
            return redirect()->route('admin.testimonial.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Testimonial update failed', [
                'error' => $e->getMessage(),
                'testimonial_id' => $id,
            ]);

            toastr()->error('Something went wrong while updating testimonial');
            return redirect()->back()->withInput();
        }
    }



    /**
     * Update the title for the testimonial section.
     * This method updates the section titles for the daily offer page.
     */
    public function updateTitle(Request $request)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'testimonial_top_title' => 'max:255',
            'testimonial_main_title' => 'max:255',
            'testimonial_sub_title' => 'max:255',
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
            $testimonial = Testimonial::findOrFail($id);
            $testimonial->delete($id);

            // delet image attached
            $this->removeImage($testimonial, 'testimonials');

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong'], 200);
        }
    }
}