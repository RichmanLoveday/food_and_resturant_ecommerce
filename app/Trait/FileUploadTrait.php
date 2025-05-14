<?php

namespace App\Trait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait FileUploadTrait
{
    /**
     * Upload an image to a specified path.
     *
     * @param Request $request
     * @param string $inputName
     * @param string $path
     * @param string|null $oldPath
     * @return string|null
     */
    public function uploadImage(Request $request, $inputName, $path = '/uploads', $oldPath = null)
    {
        $directory = public_path($path);

        //? Ensure the directory exists or create it
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if ($request->hasFile($inputName)) {
            //? upload new image
            $image = $request->file($inputName);
            $text = $image->getClientOriginalExtension();
            $imageName = 'media_' . uniqid() . '.' . $text;

            $image->move(public_path($path), $imageName);

            //? delete old image if exists
            if (!is_null($oldPath) && File::exists(public_path($oldPath))) {
                File::delete(public_path($oldPath));
            }

            return $path . '/' . $imageName;
        }

        return null;
    }


    /**
     * Remove an image from the specified path.
     *
     * @param string $path
     * @return void
     */
    public function removeImage(string $path): void
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
