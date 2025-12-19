<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutUpdateRequest;
use App\Models\About;
use App\Traits\FileUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $about = About::first();
        return view('admin.about.index', compact('about'));
    }


    public function update(AboutUpdateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Update or create the About record
            $about = About::updateOrCreate(
                ['id' => 1],
                [
                    'title' => $request->title,
                    'main_title' => $request->main_title,
                    'description' => $request->description,
                    'video_link' => $request->video_link,
                ]
            );

            $imagePath = $this->uploadImage(
                $request,
                'image',
                $about,
                'about_images'
            );

            // 3️⃣ Update image field if upload succeeded
            if (!is_null($imagePath)) {
                $about->image = $imagePath;
                $about->save();
            }

            DB::commit();

            toastr()->success('Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove newly uploaded media if needed
            if (isset($about) && method_exists($about, 'clearMediaCollection')) {
                $about->clearMediaCollection('about_images');
            }

            \Log::error('About update failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to update About section');
            return redirect()->back()->withInput();
        }
    }
}