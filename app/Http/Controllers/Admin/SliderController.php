<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SliderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderCreateRequest;
use App\Http\Requests\Admin\SliderUpdateRequest;
use App\Models\Slider;
use App\Traits\FileUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(SliderDataTable $dataTable)
    {
        return $dataTable->render('admin.slider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(SliderCreateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Create slider row first
            $slider = new Slider();
            $slider->offer = $request->offer;
            $slider->title = $request->title;
            $slider->sub_title = $request->sub_title;
            $slider->short_description = $request->short_description;
            $slider->button_link = $request->button_link;
            $slider->status = $request->status ? 1 : 0;
            $slider->save(); // save first for Spatie or uploadImage

            // 2️⃣ Upload image after saving
            $imagePath = $this->uploadImage(
                $request,
                'image',
                $slider,
                'sliders'
            );

            if (!is_null($imagePath)) {
                $slider->image = $imagePath;
                $slider->save();
            }

            DB::commit();

            toastr()->success('Slider created successfully');
            return to_route('admin.slider.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Slider creation failed', [
                'error' => $e->getMessage(),
            ]);

            toastr()->error('Something went wrong while creating slider');
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
        $slider = Slider::findOrFail($id);
        return view('admin.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(SliderUpdateRequest $request, string $id): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Fetch slider
            $slider = Slider::findOrFail($id);

            // 2️⃣ Upload image if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $slider,
                    'sliders'
                );
            }

            // 3️⃣ Update slider fields
            $slider->image = $imagePath ?? $slider->image;
            $slider->offer = $request->offer;
            $slider->title = $request->title;
            $slider->sub_title = $request->sub_title;
            $slider->short_description = $request->short_description;
            $slider->button_link = $request->button_link;
            $slider->status = $request->status ? 1 : 0;
            $slider->save();

            DB::commit();

            toastr()->success('Slider updated successfully');
            return redirect()->route('admin.slider.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Slider update failed', [
                'error' => $e->getMessage(),
                'slider_id' => $id,
            ]);

            toastr()->error('Something went wrong while updating slider');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $slider = Slider::findOrFail($id);
            $slider->delete();

            $this->removeImage(
                $slider,
                "sliders",
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
}
