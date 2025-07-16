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
            // ->addColumn('action', function ($row) {
            //     return $this->checkrights($row);
            // })
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
            ->editColumn('left_date', function ($row) {
                return ($row->left_date != '00-00-0000') ? $row->left_date : null;
            })
            ->rawColumns(['is_active', 'first_name','left_date']);
    }

    // Currently not in use : checkrights function

    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $editurl = route('employee.edit', [$row->id]);
        $deleteurl = route('employee.destroy', [$row->id]);
        $rejoinurl = route('employeeRejoin', [$row->id]);

        if ($user->hasAnyAccess(['users.info', 'employee.edit', 'employee.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center"><div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions"><a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-bold-more-hor"></i></a><div class="dropdown-menu m-0 dropdown-menu-right" style=""><ul class="navi navi-hover">';
        }

        if ((isset($row->left_date) && $row->left_date != '00-00-0000' && $row->recruit_again == 'Yes' && ($row->rejoin_date == '0000-00-00' || $row->rejoin_date == null))) {
            $menu .= '<li class="navi-item"><a href="' . $rejoinurl . '"  class="navi-link"><span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('employee.rejoin') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['employee.edit', 'users.superadmin']) && ($row->left_date == '00-00-0000' || $row->left_date == null)) {
            $menu .= '<li class="navi-item"><a href="' . $editurl . '"  class="navi-link"><span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['employee .delete', 'users.superadmin']) && ($row->left_date == '00-00-0000' || $row->left_date == null)) {
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
        $model = Employee::leftJoin('employee_addresses', 'employees.id', '=', 'employee_addresses.employee_id')
            ->leftJoin('employee_documents', 'employees.id', '=', 'employee_documents.employee_id')
            ->leftjoin('departments', 'departments.id', '=', 'employees.department_id')
            ->leftjoin('designations', 'designations.id', '=', 'employees.designation_id')
            ->select([
                'employees.id as id', 'employees.first_name as first_name', 'employees.last_name as last_name', 'departments.name as department_id', 'designations.name as designation_id', 'employees.rejoin_date as rejoin_date', 'employees.recruit_again',
                DB::raw("DATE_FORMAT(employees.birth_date, '%d-%m-%Y') as birth_date"),
                DB::raw("DATE_FORMAT(employees.join_date, '%d-%m-%Y') as join_date"),
                DB::raw("DATE_FORMAT(employees.left_date, '%d-%m-%Y') as left_date"),
                'employees.employee_code as employee_code',
                'employees.mobile as mobile1',
                'employee_addresses.permanent_address as permanent_address',
                'employee_documents.aadhar_card_no as aadhar_card_no',
                'employee_documents.pan_card_no as pan_card_no',
                'employees.is_active',
                'employees.middle_name'
            ]);

        $date = (request()->get('filterjoinDate') != '') ? explode(' | ', request()->get('filterjoinDate')) : '';
        $from_date = ($date != '') ? date('Y-m-d', strtotime($date[0])) : '';
        $to_date = ($date != '') ? date('Y-m-d', strtotime($date[1])) : '';
        if ($from_date != '' && $to_date != '') {
            $model->whereBetween('employees.join_date', [$from_date, $to_date]);
        }

        if (request()->get('personNameFilter') != '') {
            $model->where('employees.id', request()->get('personNameFilter'));
        }
        if (request()->get('departmentFilter') != '') {
            $model->where('employees.department_id', [request()->get('departmentFilter')]);
        }
        if (request()->get('designationFilter') != '') {
            $model->where('employees.designation_id', [request()->get('designationFilter')]);
        }

        if (request()->get('statusFilter') != '') {
            $model->where('employees.is_active', [request()->get('statusFilter')]);
        }
        if (request()->get('employee_code', false)) {
            $model->where('employee_code', 'like', "%" . request()->get("employee_code") . "%");
        }
        if (request()->get('person_name', false)) {
            $model->whereRaw("concat(first_name, ' ',CASE WHEN middle_name IS NOT NULL THEN  middle_name ELSE '' END,' ', last_name) like '%" . request()->get("person_name") . "%' ");
        }
        if (request()->get('mobile1', false)) {
            $model->where('mobile', 'like', "%" . request()->get("mobile1") . "%");
        }
        if (request()->get('department_id', false)) {
            $model->where('departments.name', 'like', "%" . request()->get("department_id") . "%");
        }
        if (request()->get('designation_id', false)) {
            $model->where('designations.name', 'like', "%" . request()->get("designation_id") . "%");
        }
        if (request()->get('birth_date', false)) {
            $model->where(DB::raw("DATE_FORMAT(birth_date,'%d-%m-%Y')"), 'like', '%' . request()->get('birth_date') . '%');
        }
        if (request()->get('join_date', false)) {
            $model->where(DB::raw("DATE_FORMAT(join_date,'%d-%m-%Y')"), 'like', '%' . request()->get('join_date') . '%');
        }
        if (request()->get('left_date', false)) {
            $model->where(DB::raw("DATE_FORMAT(left_date,'%d-%m-%Y')"), 'like', '%' . request()->get('left_date') . '%');
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
