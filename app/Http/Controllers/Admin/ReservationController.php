<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ReservationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(ReservationDataTable $dataTable)
    {
        return $dataTable->render('admin.reservation.index');
    }


    public function update(Request $request)
    {
        // dd($request->all());
        $reservation = Reservation::findOrFail($request->id);
        $reservation->status = $request->status;
        $reservation->save();

        return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
    }

    public function destroy($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();

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