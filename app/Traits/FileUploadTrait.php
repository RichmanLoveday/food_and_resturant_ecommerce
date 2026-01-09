<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\HasMedia;

trait FileUploadTrait
{
    protected function realPublicPath(string $path = ''): string
    {
        return rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . ltrim($path, '/');
    }

    public function uploadImage(
        Request $request,
        string $inputName,
        HasMedia $model,
        string $collection = 'default'
    ): ?string {
        if ($request->hasFile($inputName) && $request->file($inputName)->isValid()) {

            $file = $request->file($inputName);
            $newName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $this->removeImage($model, $collection);

            $media = $model->addMedia($file)
                ->usingFileName($newName)
                ->toMediaCollection($collection);

            // 🔥 REAL htdocs path
            $destinationDir = $this->realPublicPath('storage/' . $media->id);
            $destination = $destinationDir . '/' . $media->file_name;

            if (!File::exists($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }

            File::copy($media->getPath(), $destination);

            return asset('storage/' . $media->id . '/' . $media->file_name);
        }

        return null;
    }

    public function removeImage(HasMedia $model, string $collection = 'default'): void
    {
        foreach ($model->getMedia($collection) as $media) {
            File::deleteDirectory(
                $this->realPublicPath('storage/' . $media->id)
            );
        }

        $model->clearMediaCollection($collection);
    }
}
