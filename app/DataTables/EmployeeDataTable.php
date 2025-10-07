<?php

namespace App\DataTables;

use App\Models\Employee;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EmployeeDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return $this->checkrights($row);
            })
            ->editColumn('is_active', function ($row) {
                return getEmployeeStatusHtml($row, 'employee.edit');
            })
            ->editColumn('first_name', function ($row) {
                $copyHtml = ' <a href="javascript:void(0)"
                class="btn btn-hover-light-primary btn-sm btn-icon copy-btn">
                <i class="fas fa-copy"></i></a> ';
                $user = Sentinel::getUser();

                if ($user->hasAnyAccess(['employee.view', 'users.superadmin'])) {
                    return '<a href="' . route('employee.show', [$row->id]) . '"  class="navi-link">' .
                        '<span class="navi-text emp-text">' . $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name . '</span></a> ' . $copyHtml;
                } else {
                    return $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                }
            })
            ->rawColumns(['is_active', 'first_name']);
    }

    // Currently not in use : checkrights function

    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $editurl = route('employee.edit', [$row->id]);
        $deleteurl = route('employee.destroy', [$row->id]);

        if ($user->hasAnyAccess(['users.info', 'employee.edit', 'employee.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center"><div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions"><a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-bold-more-hor"></i></a><div class="dropdown-menu m-0 dropdown-menu-right" style=""><ul class="navi navi-hover">';
        }

        if ($user->hasAnyAccess(['employee.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editurl . '"  class="navi-link"><span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['employee .delete', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $deleteurl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link"><span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span>' .
                '</a></li>';
        }

        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }

        if ($user->hasAnyAccess(['users.info', 'employee.edit', 'employee.delete', 'users.superadmin'])) {
            $menu .= "</ul></div></div></td>";
        }
        $menu .= "</ul></div></div></td>";

        return $menu;
    }

    public function query(Employee $model)
    {
        $model = Employee::select([
                'employees.id as id', 
                'employees.first_name as first_name', 
                'employees.middle_name as middle_name',
                'employees.last_name as last_name',
                'employees.employee_code as employee_code',
                'employees.mobile as mobile1',
                'employees.address as address',
                'employees.aadhar_card_no as aadhar_card_no',
                'employees.is_active',
            ]);
        
        if (request()->get('statusFilter') != '') {
            $model->where('employees.is_active', [request()->get('statusFilter')]);
        }
        if (request()->get('employee_code', false)) {
            $model->where('employee_code', 'like', "%" . request()->get("employee_code") . "%");
        }
        
        if (request()->get('mobile1', false)) {
            $model->where('mobile', 'like', "%" . request()->get("mobile1") . "%");
        }
        return $this->applyScopes($model);
    }

    public function html()
    {
        return $this->builder()
            ->parameters(['searching' => false, 'dom' => '<"wrapper"B>lfrtip', 'buttons' => ['excel', 'pdf'],])
            ->columns($this->getColumns())
            ->ajax('');
    }

    protected function getColumns()
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('person_name'),
        ];
    }

    protected function filename(): string
    {
        return 'Employee_' . date('YmdHis');
    }
}
