<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppDownloadSectionCreateRequest;
use App\Models\AppDownloadSection;
use App\Traits\FileUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        //? Validate and handle the image upload
        $imagePath = $this->uploadImage(
            $request,
            'image',
            '/uploads/app-download',
            $request->old_image
        );

        $backgroundPath = $this->uploadImage(
            $request,
            'background',
            '/uploads/app-download',
            $request->old_background,
        );

        //? create or update the app download section
        $appDownloadSection = [
            'image' => !empty($imagePath) ? $imagePath : $request->old_image,
            'background' => !empty($backgroundPath) ? $backgroundPath : $request->old_background,
            'title' => $request->title,
            'short_description' => $request->short_description,
            'apple_store_link' => $request->apple_store_link,
            'play_store_link' => $request->play_store_link,
        ];

        //? update or create
        AppDownloadSection::updateOrCreate(
            ['id' => 1],
            $appDownloadSection
        );

        toastr()->success('Updated successfully');

        //? Redirect back to the index page
        return redirect()->route('admin.app-download.index');
    }
}