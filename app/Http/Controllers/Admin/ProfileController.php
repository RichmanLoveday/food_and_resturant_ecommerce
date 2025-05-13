<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfilePasswordUpdate;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Trait\FileUploadTrait;
use Auth;
use Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use FileUploadTrait;

    public function index(): View
    {
        return view('admin.profile.index');
    }


    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {

        // dd($request->all());
        $user = Auth::user();

        //? get uploaded image path
        $imagePath = $this->uploadImage($request, 'avatar', '/uploads/profile_photo');

        //? update user model
        $user->name = $request->name;
        $user->email = $request->email;
        $user->avatar = isset($imagePath) ? $imagePath : $user->avatar;
        $user->save();

        //? flash a toastr message
        toastr('Updated successfully', 'success');

        return redirect()->back();
    }

    public function updatePassword(ProfilePasswordUpdate $request): RedirectResponse
    {
        // dd($request->all());
        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        //? flass a toastr message
        toastr('Password updated successfully', 'success');
        return redirect()->back();
    }
}
