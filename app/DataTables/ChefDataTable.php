<?php

namespace App\DataTables;

use App\Models\Chefs;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ChefDataTable extends DataTable
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
                $edit = "<a href='" . route('admin.chef.edit', $query->id) . "' class='btn btn-primary mx-1'><i class='fas fa-edit'></i></a>";
                $delete = "<a href='" . route('admin.chef.destroy', $query->id) . "' class='btn btn-danger mx-1 delete-item'><i class='fas fa-trash'></i></a>";

                return $edit . ' ' . $delete;
            })->addColumn('image', function ($query) {
                return "<img src='" . asset($query->image) . "' class='img-fluid rounded-circle' style='width: 60px; height: 60px;'>";
            })->addColumn('status', function ($query) {
                return $query->status ? "<span class='badge badge-primary'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>";
            })->addColumn('show_at_home', function ($query) {
                return $query->show_at_home ? "<span class='badge badge-primary'>Yes</span>" : "<span class='badge badge-danger'>No</span>";
            })->rawColumns(['image', 'action', 'status', 'show_at_home'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Chefs $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('chef-table')
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

            Column::make('id')->width(100)->addClass('text-left'),
            Column::make('image'),
            Column::make('name'),
            Column::make('title'),
            Column::make('show_at_home'),
            Column::make('status'),
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
        return 'Chef_' . date('YmdHis');
    }
}