<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutUpdateRequest;
use App\Models\About;
use App\Traits\FileUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        //? upload image and get image path
        $imagePath = $this->uploadImage(
            $request,
            'image',
            '/uploads/about',
            $request->old_image
        );

        //? update or create a new data, if id row is found
        About::updateOrCreate(
            ['id' => 1],
            [
                'image' => !empty($imagePath) ? $imagePath : $request->old_image,
                'title' => $request->title,
                'main_title' => $request->main_title,
                'description' => $request->description,
                'video_link' => $request->video_link,
            ]
        );

        toastr()->success('Updated Successfully');

        return redirect()->back();
    }
}
