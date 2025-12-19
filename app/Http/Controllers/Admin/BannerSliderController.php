<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BannerSliderDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerCreateRequest;
use App\Http\Requests\Admin\UpdateBannerSliderRequest;
use App\Models\BannerSlider;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    public function store(BannerCreateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Create banner
            $bannerSlider = new BannerSlider();
            $bannerSlider->title = $request->title;
            $bannerSlider->sub_title = $request->sub_title;
            $bannerSlider->status = $request->status ? 1 : 0;
            $bannerSlider->url = $request->url;

            // 2️⃣ Save first (required for media handling)
            $bannerSlider->save();

            // 3️⃣ Upload image if provided
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $bannerSlider,
                    'banner_sliders'
                );

                if (!is_null($imagePath)) {
                    $bannerSlider->image = $imagePath;
                    $bannerSlider->save();
                }
            }

            DB::commit();

            toastr()->success('Created successfully');
            return redirect()->route('admin.banner-slider.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove uploaded media if needed
            if (isset($bannerSlider) && method_exists($bannerSlider, 'clearMediaCollection')) {
                $bannerSlider->clearMediaCollection('banner_sliders');
            }

            \Log::error('Banner store failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to create banner');
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
        $bannerSlider = BannerSlider::findOrFail($id);
        return view('admin.banner-slider.edit', compact('bannerSlider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerSliderRequest $request, string $id): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Find banner
            $bannerSlider = BannerSlider::findOrFail($id);
            $bannerSlider->title = $request->title;
            $bannerSlider->sub_title = $request->sub_title;
            $bannerSlider->status = $request->status ? 1 : 0;
            $bannerSlider->url = $request->url;

            // 2️⃣ Upload new image if provided
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $bannerSlider,
                    'banner_sliders'
                );

                if (!is_null($imagePath)) {
                    $bannerSlider->image = $imagePath;
                }
            }

            // 3️⃣ Save updates
            $bannerSlider->save();

            DB::commit();

            toastr()->success('Updated successfully');
            return redirect()->route('admin.banner-slider.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove newly uploaded media if needed
            if (isset($bannerSlider) && method_exists($bannerSlider, 'clearMediaCollection')) {
                $bannerSlider->clearMediaCollection('banner_sliders');
            }

            \Log::error('Banner update failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to update banner');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bannerSlider = BannerSlider::findOrFail($id);

            // Optional: remove associated image first
            $this->removeImage($bannerSlider);

            // Delete banner
            $bannerSlider->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully!',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Banner deletion failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}