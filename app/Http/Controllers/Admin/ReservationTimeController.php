<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReservationTimeDataTable;
use App\Http\Controllers\Controller;
use App\Models\ReservationTime;
use App\Traits\FileUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationTimeController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ReservationTimeDataTable $dataTables): View|JsonResponse
    {
        return $dataTables->render('admin.reservation.reservation-time.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.reservation.reservation-time.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
            'status' => ['required', 'boolean'],
        ]);

        $time = new ReservationTime();
        $time->start_time = $request->start_time;
        $time->end_time = $request->end_time;
        $time->save();

        toastr()->success('Created Successfully');
        return redirect()->route('admin.reservation-time.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $time = ReservationTime::findOrFail($id);
        return view('admin.reservation.reservation-time.edit', compact('time'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
            'status' => ['required', 'boolean'],
        ]);

        $time = ReservationTime::findOrFail($id);
        $time->start_time = $request->start_time;
        $time->end_time = $request->end_time;
        $time->save();

        toastr()->success('Updated Successfully');
        return redirect()->route('admin.reservation-time.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $time = ReservationTime::findOrFail($id);
            $time->delete();
            // $this->removeImage(
            //     $time,
            //     "reservation-time",
            //     // $time->image
            // );

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}