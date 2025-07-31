<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TodaysOrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('customer_name', function (Order $query) {
                return  $query->user?->name;
            })
            ->addColumn('grand_total', function (Order $query) {
                return   $query->grand_total . ' ' . strtoupper($query->currency_name);
            })
            ->addColumn('order_status', function ($query) {
                if ($query->order_status === 'pending') {
                    return "<span class='badge badge-warning'>Pending</span>";
                } elseif ($query->order_status === 'delivered') {
                    return "<span class='badge badge-success'>Delivered</span>";
                } elseif ($query->order_status === 'declined') {
                    return "<span class='badge badge-danger'>Declined</span>";
                } elseif ($query->order_status === 'in_process') {
                    return "<span class='badge badge-info'>In Process</span>";
                }
            })
            ->addColumn('payment_status', function ($query) {
                if (strtoupper($query->payment_status) == 'COMPLETED' || strtoupper($query->payment_status) == 'COMPLETE') {
                    return "<span class='badge badge-success'>COMPLETED</span>";
                } elseif (strtoupper($query->payment_status) == 'PENDING') {
                    return "<span class='badge badge-warning'>PENDING</span>";
                } elseif (strtoupper($query->payment_status) == 'FAILED') {
                    return "<span class='badge badge-danger'>FAILED</span>";
                }
            })
            ->addColumn('date', function ($query) {
                return date('d-m-Y', strtotime($query->created_at));
            })
            ->addColumn('action', function ($query) {
                $view = "<a href='" . route('admin.order.show', $query->id) . "' class='btn btn-primary mx-1'><i class='fas fa-eye'></i></a>";

                $status = "<a id='order_status' href='javascript:;' class='btn btn-warning mx-1' data-id='" . $query->id . "'><i class='fas fa-truck-loading' data-toggle='modal' data-target='#order_modal'></i></a>";

                $delete = "<a href='" . route('admin.orders.destroy', $query->id) . "' class='btn btn-danger mx-1 delete-item'><i class='fas fa-trash'></i></a>";

                return $view . ' ' . $status . ' ' . $delete;
            })
            ->setRowId('id')
            ->rawColumns(['action', 'customer_name', 'grand_total', 'order_status', 'payment_status', 'date', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->whereDate('created_at', now()->format('Y-m-d'))
            ->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('order-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('invoice_id'),
            Column::make('customer_name'),
            Column::make('product_qty'),
            Column::make('grand_total'),
            Column::make('order_status'),
            Column::make('payment_status'),
            Column::make('date'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(180)
                ->addClass('text-center'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}