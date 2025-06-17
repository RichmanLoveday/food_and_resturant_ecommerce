<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
    }


    public function show($id)
    {
        $order = Order::with(['user', 'deliveryArea', 'orderItems'])
            ->findOrFail($id);

        // dd($order->orderItems);
        return view('admin.order.show', compact('order'));
    }


    public function orderStatusUpdate(Request $request, string|int $id): RedirectResponse|JsonResponse
    {
        //dd($request->all());
        //? validate input fields given for update
        $request->validate([
            'payment_status' => ['required', 'in:pending,completed'],
            'order_status' => ['required', 'in:pending,in_process,delivered,declined'],
        ]);

        //? find specific order and update
        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        $order->order_status = $request->order_status;
        $order->save();

        //? if request is ajax, return json response
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Order status updated successfully!',
            ]);
        }

        //? if request is not ajax, redirect back with success message
        toastr()->success('Status updated successfully!');
        return redirect()->back();
    }


    public function getOrderStatus(Request $request, string|int $id)
    {
        try {
            $order = Order::select(['order_status', 'payment_status'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found or an error occurred.',
            ], 404);
        }
    }


    public function destroy(string|int $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Order deleted successfully!'
            ]);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}