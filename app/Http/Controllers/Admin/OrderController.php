<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DeclinedOrderDataTable;
use App\DataTables\DeliveredOrderDataTable;
use App\DataTables\InProcessOrderDataTable;
use App\DataTables\OrderDataTable;
use App\DataTables\PendingOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @param OrderDataTable $dataTable
     * @return \Illuminate\Contracts\View\View|JsonResponse
     */
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.index');
    }


    /**
     * Display the specified order.
     *
     * @param int|string $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $order = Order::with(['user', 'deliveryArea', 'orderItems'])
            ->findOrFail($id);

        // dd($order->orderItems);
        return view('admin.order.show', compact('order'));
    }


    /**
     * Update the order status and payment status.
     *
     * @param Request $request
     * @param string|int $id
     * @return RedirectResponse|JsonResponse
     */
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


    /**
     * Get the order status and payment status.
     * @param Request $request
     * @param string|int $id
     * @return JsonResponse
     */
    public function getOrderStatus(Request $request, string|int $id): JsonResponse
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


    /**
     * Display a listing of pending orders.
     */
    public function pendingOrders(PendingOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.pending-orders-index');
    }


    /**
     * Display a listing of in-process orders.
     *
     * @param OrderDataTable $dataTable
     * @return \Illuminate\Contracts\View\View|JsonResponse
     */
    public function inProcessOrders(InProcessOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.inprocess-orders-index');
    }


    /**
     * Display a listing of delivered orders.
     *
     * @param DeliveredOrderDataTable $dataTable
     * @return \Illuminate\Contracts\View\View|JsonResponse
     */
    public function deliveredOrders(DeliveredOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.delivered-orders-index');
    }


    public function declinedOrders(DeclinedOrderDataTable $dataTable)
    {
        return $dataTable->render('admin.order.declined-orders-index');
    }


    /**
     * Remove the specified order from storage.
     */
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
