<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia;

trait FileUploadTrait
{
    /**
     * Upload image using Spatie Media Library
     */
    public function uploadImage(
        Request $request,
        string $inputName,
        HasMedia $model,
        string $collection = 'default'
    ): ?string {
        if ($request->hasFile($inputName) && $request->file($inputName)->isValid()) {
            $file = $request->file($inputName);
            // Generate random + timestamp filename
            $newName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $model->clearMediaCollection($collection);
            $media = $model->addMedia($file)
                ->usingFileName($newName)
                ->toMediaCollection($collection);

            return 'storage/' . $media->id . '/' . $media->file_name; // local storage path
        }

        return null;
    }

    /**
     * Remove all images in a collection
     */
    public function removeImage(HasMedia $model, string $collection = 'default'): void
    {
        $model->clearMediaCollection($collection);
    }
}
