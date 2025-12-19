<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfilePasswordUpdate;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Traits\FileUploadTrait;
use Auth;
use Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    use FileUploadTrait;

    public function index(): View
    {
        return view('admin.profile.index');
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Get the authenticated user
            $user = Auth::user();

            // 2️⃣ Upload avatar if provided
            $imagePath = null;
            if ($request->hasFile('avatar')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'avatar',
                    $user,
                    'profile_photo'
                );
            }

            // 3️⃣ Update user fields
            $user->name = $request->name;
            $user->email = $request->email;
            $user->avatar = $imagePath ?? $user->avatar;

            $user->save();

            DB::commit();

            toastr()->success('Updated successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Profile update failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            toastr()->error('Something went wrong while updating profile');
            return redirect()->back()->withInput();
        }
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
