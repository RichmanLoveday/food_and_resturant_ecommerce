<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BannerSliderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerCreateRequest;
use App\Http\Requests\Admin\UpdateBannerSliderRequest;
use App\Models\BannerSlider;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class BannerSliderController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(BannerSliderDataTable $dataTable)
    {
        return $dataTable->render('admin.banner-slider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner-slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerCreateRequest $request)
    {
        // dd($request->all());

        //? Handle image upload and store the banner slider data
        $imagePath = $this->uploadImage($request, 'image', '/uploads/banner-sliders');

        $bannerSlider = new BannerSlider();
        $bannerSlider->image = $imagePath;
        $bannerSlider->title = $request->title;
        $bannerSlider->sub_title = $request->sub_title;
        $bannerSlider->status = $request->status ? 1 : 0;
        $bannerSlider->url = $request->url;
        $bannerSlider->save();

        //? Flash message
        toastr()->success('Created successfully');

        return redirect()->route('admin.banner-slider.index');
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
        $bannerSlider = BannerSlider::findOrFail($id);
        return view('admin.banner-slider.edit', compact('bannerSlider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerSliderRequest $request, string $id)
    {
        //? Handle image upload and store the banner slider data
        $imagePath = $this->uploadImage(
            $request,
            'image',
            '/uploads/banner-sliders',
            $request->old_path
        );

        $bannerSlider = BannerSlider::findOrFail($id);
        $bannerSlider->image = !empty($imagePath) ? $imagePath : $request->old_path;
        $bannerSlider->title = $request->title;
        $bannerSlider->sub_title = $request->sub_title;
        $bannerSlider->status = $request->status ? 1 : 0;
        $bannerSlider->url = $request->url;
        $bannerSlider->save();

        //? Flash message
        toastr()->success('Updated successfully');

        return redirect()->route('admin.banner-slider.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bannerSlider = BannerSlider::findOrFail($id);
            $bannerSlider->delete();
            $this->removeImage($bannerSlider->image);

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
