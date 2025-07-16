<?php

namespace App\DataTables;

use App\Models\Designation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DesignationDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return $this->checkrights($row);
            })
            ->editColumn('is_active', function ($row) {
                return getStatusHtml($row, 'designation.edit');
            })->editColumn('name', function ($row) {
                // $user = Sentinel::getUser();
                // if ($user->hasAnyAccess(['designation.view', 'users.superadmin'])) {
                //     return '<a href="' .route('designation.show', [$row->id]) . '"  class="navi-link">' .
                //         '<span class="navi-text">' .$row->name . '</span>' .
                //     '</a>';
                // } else{
                return $row->name;
                // }
            })
            ->editColumn('department', function ($row) {
                return $row->department->name ?? '';
            })
            ->editColumn('employee', function ($row) {
                return '<a href="' . route('designation.show', [$row->id]) . '"  class="navi-link" target="_blank">' .
                    '<span class="navi-text">' . $row->employee->count() . '</span>' .
                    '</a>';
            })
            ->rawColumns(['action', 'is_active', 'name', 'department', 'employee']);
    }

    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $editurl = route('designation.edit', [$row->id]);
        $deleteurl = route('designation.destroy', [$row->id]);

        if ($user->hasAnyAccess(['users.info', 'designation.edit', 'designation.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center"><div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions"><a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-bold-more-hor"></i></a><div class="dropdown-menu m-0 dropdown-menu-right" style=""><ul class="navi navi-hover">';
        }


        if ($user->hasAnyAccess(['designation.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editurl . '" data-toggle="modal" data-target-modal="#commonModalID"  data-url="' . $editurl . '" class="call-modal navi-link"><span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['designation.delete', 'users.superadmin']) && $row->employee_count <= 0) {
            $menu .= '<li class="navi-item"><a href="' . $deleteurl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link"><span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span></a></li>';
        }
        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }

        if ($user->hasAnyAccess(['users.info', 'designation.edit', 'designation.delete', 'users.superadmin'])) {
            $menu .= "</ul></div></div></td>";
        }
        $menu .= "</ul></div></div></td>";

        return $menu;
    }


    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Designation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Designation $model)
    {
        $model = Designation::with(['department', 'employee' => function ($qry) {
            $qry->where('is_active', 'Yes');
        }])->withCount('employee');

        if (request()->get('name', false)) {
            $model->where('name', 'like', "%" . request()->get("name") . "%");
        }
        if (request()->get('departmentFilter') != '') {
            $model->where('department_id', [request()->get('departmentFilter')]);
        }
        return $this->applyScopes($model->newQuery());
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->parameters(['searching' => false, 'dom' => '<"wrapper"B>lfrtip', 'buttons' => ['excel', 'pdf'],])
            ->columns($this->getColumns())
            ->ajax('');
        // return $this->builder()
        //             ->setTableId('designation-table')
        //             ->columns($this->getColumns())
        //             ->minifiedAjax()
        //             ->dom('Bfrtip')
        //             ->orderBy(1)
        //             ->buttons(
        //                 Button::make('create'),
        //                 Button::make('export'),
        //                 Button::make('print'),
        //                 Button::make('reset'),
        //                 Button::make('reload')
        //             );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('name'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Designation_' . date('YmdHis');
    }
}
