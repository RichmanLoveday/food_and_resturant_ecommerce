<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppDownloadSectionCreateRequest;
use App\Models\AppDownloadSection;
use App\Traits\FileUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppDownloadSectionController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $appDownloadSection = AppDownloadSection::first();
        return view('admin.app-download-section.index', compact('appDownloadSection'));
    }


    public function store(AppDownloadSectionCreateRequest $request): RedirectResponse
    {


        DB::beginTransaction();

        try {
            // 1️⃣ Prepare data for updateOrCreate
            $data = [
                'title' => $request->title,
                'short_description' => $request->short_description,
                'apple_store_link' => $request->apple_store_link,
                'play_store_link' => $request->play_store_link,
            ];

            // 2️⃣ Update or create the section
            $appDownloadSection = AppDownloadSection::updateOrCreate(
                ['id' => 1],
                $data
            );

            // 3️⃣ Upload image if provided
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $appDownloadSection,
                    'app_download_images'
                );

                if (!is_null($imagePath)) {
                    $appDownloadSection->image = $imagePath;
                    $appDownloadSection->save();
                }
            }

            // 4️⃣ Upload background if provided
            if ($request->hasFile('background')) {
                $backgroundPath = $this->uploadImage(
                    $request,
                    'background',
                    $appDownloadSection,
                    'app_download_backgrounds'
                );

                if (!is_null($backgroundPath)) {
                    $appDownloadSection->background = $backgroundPath;
                    $appDownloadSection->save();
                }
            }

            DB::commit();

            toastr()->success('Updated successfully');
            return redirect()->route('admin.app-download.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove uploaded media if needed
            if (isset($appDownloadSection) && method_exists($appDownloadSection, 'clearMediaCollection')) {
                $appDownloadSection->clearMediaCollection('app_download_images');
                $appDownloadSection->clearMediaCollection('app_download_backgrounds');
            }

            \Log::error('AppDownloadSection store failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to update App Download Section');
            return redirect()->back()->withInput();
        }
    }
}
