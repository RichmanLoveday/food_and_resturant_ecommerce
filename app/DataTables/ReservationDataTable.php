<?php

namespace App\DataTables;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ReservationDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $delete = "<a href='" . route('admin.reservation.destroy', $query->id) . "' class='btn btn-danger mx-1 delete-item'><i class='fas fa-trash'></i></a>";

                return $delete;
            })->addColumn('status', function ($query) {
                $status = $query->status;

                $html = "<select class='form-control reservation_status' data-id='" . $query->id . "'>
                    <option value='pending' " . ($status == 'pending' ? 'selected' : '') . ">Pending</option>
                    <option value='approved' " . ($status == 'approved' ? 'selected' : '') . ">Approved</option>
                    <option value='completed' " . ($status == 'completed' ? 'selected' : '') . ">Complete</option>
                    <option value='cancel' " . ($status == 'cancel' ? 'selected' : '') . ">Cancel</option>
                </select>";


                return $html;
            })->addColumn('created_at', function ($query) {
                return date('d-m-y', strtotime($query->created_at));
            })->addColumn('date', function ($query) {
                return date('d-m-y', strtotime($query->date));
            })->rawColumns(['action', 'status', 'created_at', 'date', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Reservation $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reservation-table')
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
            Column::make('name'),
            Column::make('phone'),
            Column::make('date'),
            Column::make('time'),
            Column::make('created_at'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Reservation_' . date('YmdHis');
    }
}