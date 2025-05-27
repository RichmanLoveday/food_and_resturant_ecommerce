<?php

namespace App\DataTables;

use App\Models\DeliveryArea;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DeliveryAreaDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (DeliveryArea $query) {
                $edit = "<a href='" . route('admin.delivery-area.edit', $query->id) . "' class='btn btn-primary mx-1'><i class='fas fa-edit'></i></a>";
                $delete = "<a href='" . route('admin.delivery-area.destroy', $query->id) . "' class='btn btn-danger mx-1 delete-item'><i class='fas fa-trash'></i></a>";

                return $edit . ' ' . $delete;
            })
            ->addColumn('status', function (DeliveryArea $query) {
                return $query->status ? "<span class='badge badge-primary'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>";
            })
            ->addColumn('delivery_fee', function (DeliveryArea $query) {
                return currencyPosition($query->delivery_fee);
            })
            ->setRowId('id')
            ->rawColumns(['action', 'status', 'delivery_fee']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DeliveryArea $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('deliveryarea-table')
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
            Column::make('id')
                ->addClass('text-left'),
            Column::make('area_name')
                ->addClass('text-left'),
            Column::make('delivery_fee')
                ->addClass('text-center'),
            Column::make('min_delivery_time'),
            Column::make('max_delivery_time'),
            Column::make('status')
                ->addClass('text-center'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'DeliveryArea_' . date('YmdHis');
    }
}