<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CounterUpdateRequest;
use App\Models\Counter;
use App\Traits\FileUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{
    use FileUploadTrait;
    public function index()
    {
        $counter = Counter::first();
        return view('admin.counter.index', compact('counter'));
    }

    public function update(CounterUpdateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Update or create the counter
            $counter = Counter::updateOrCreate(
                ['id' => 1],
                [
                    'counter_icon_one' => $request->counter_icon_one,
                    'counter_count_one' => $request->counter_count_one,
                    'counter_name_one' => $request->counter_name_one,

                    'counter_icon_two' => $request->counter_icon_two,
                    'counter_count_two' => $request->counter_count_two,
                    'counter_name_two' => $request->counter_name_two,

                    'counter_icon_three' => $request->counter_icon_three,
                    'counter_count_three' => $request->counter_count_three,
                    'counter_name_three' => $request->counter_name_three,

                    'counter_icon_four' => $request->counter_icon_four,
                    'counter_count_four' => $request->counter_count_four,
                    'counter_name_four' => $request->counter_name_four,
                ]
            );

            // 2️⃣ Upload new background if provided
            if ($request->hasFile('background')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'background',
                    $counter,
                    'counter'
                );

                // 3️⃣ Update background path if upload succeeded
                if (!is_null($imagePath)) {
                    $counter->background = $imagePath;
                    $counter->save();
                }
            }

            DB::commit();

            toastr()->success('Updated Successfully!');
            return redirect()->route('admin.counter.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Optional: remove newly uploaded media if needed
            $this->removeImage($counter);

            \Log::error('Counter update failed', ['error' => $e->getMessage()]);

            toastr()->error('Failed to update counter');
            return redirect()->back()->withInput();
        }
    }
}