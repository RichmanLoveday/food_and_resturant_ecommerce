<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfilePasswordUpdate;
use App\Http\Requests\Frontend\ProfilePasswordRequest;
use App\Http\Requests\Frontend\ProfileUpdateRequest;
use App\Trait\FileUploadTrait;
use Auth;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use FileUploadTrait;

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        // dd($request->all());
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        //? flash a toastr message
        toastr('Profile updated successfully');

        return redirect()->back();
    }

    public function updatePassword(ProfilePasswordRequest $request): RedirectResponse
    {
        // dd($request->all());
        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        //? flass a toastr message
        toastr('Password updated successfully', 'success');
        return redirect()->back();
    }

    public function updateAvatar(Request $request): JsonResponse
    {
        // dd($request->avatar);

        /** handle image file */
        $imagePath = $this->uploadImage($request, 'avatar', 'uploads/profile_photo');

        $user = Auth::user();
        $user->avatar = $imagePath;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Avatar Updated Successfully',
            'image' => $imagePath
        ]);
    }
}