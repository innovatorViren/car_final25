<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Illuminate\Support\Facades\DB;

class UserDataTable extends DataTable
{
    protected $i = 1;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return $this->checkrights($row);
            })
            ->editColumn('rownum', function ($row) {
                return $this->i++;
            })
            ->editColumn('is_active', function ($row) {
                return getStatusHtml($row);
            })->editColumn('name', function ($row) {
                $user = Sentinel::getUser();
                if ($user->hasAnyAccess(['users.view', 'users.superadmin'])) {
                    return '<a href="' . route('users.show', [$row->id]) . '"  class="navi-link">' .
                        '<span class="navi-text">' . $row->full_name . '</span>' .
                        '</a>';
                } else {
                    return $row->full_name;
                }
            })->editColumn('email', function ($row) {
                $userPer = Sentinel::findById($row->id);
                $per = '';
                if ($userPer->hasAnyAccess(['users.superadmin'])) {
                    $per = '<span class="badge text-blue font-weight-bolder">S</span>';
                }
                return $row->email . ' ' . $per;
            })
            ->editColumn('role', function ($row) {
                return $row->role;
                $user = Sentinel::getUser();
                if ($user->hasAnyAccess(['roles.edit', 'users.superadmin'])) {
                    return '<a href="' . route('roles.edit', [$row->role_id]) . '"  class="navi-link" target="_blank">' .
                        '<span class="navi-text">' . $row->role . '</span>' .
                        '</a>';
                } else {
                    return $row->role;
                }
            })
            ->editColumn('emp_type', function ($row) {
                if ($row->emp_type == 'non-employee') {
                    return 'Non Employee';
                } else if ($row->emp_type == 'employee') {
                    return 'Employee';
                } else if ($row->emp_type == 'customer') {
                    return 'Customer';
                }
            })->rawColumns(['action', 'is_active', 'email', 'name', 'role'])
            ->orderColumn('name', function ($query, $order) {
                $query->orderBy('id', 'desc');
            });;
    }

    public function checkrights($row)
    {
        // $user = Sentinel::getUser();
        $menu = '';
        $editurl = route('users.edit', [$row->id]);
        $deleteurl = route('users.destroy', [$row->id]);
        $menu .= '<td class="text-center">
                    <div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions">
                    <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ki ki-bold-more-hor"></i>
                    </a>
                    <div class="dropdown-menu m-0 dropdown-menu-right" style="">
                        <ul class="navi navi-hover">';

        $menu .= '<li class="navi-item"><a href="' . $editurl . '" class="navi-link">' .
            '<span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span>' .
            '</a></li>';


        $menu .= getInfoHtml($row);

        /* $menu .= '<li class="navi-item"><a href="' . $deleteurl . '" class="delete-confrim navi-link">' .
                '<span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span>' .
            '</a></li>'; */

        $menu .= "</ul></div></div></td>";

        return $menu;
    }

    public function changeStatus($row)
    {
        $statusHtml = "";
        $url = route('common.change-status', [$row->id]);
        $table = "dataTableBuilder";
        if (strtoupper($row->is_active) == 'YES' && $row->is_active !== NULL) {
            $statusHtml = '<div class="text-center">
                <span class="switch switch-icon switch-md">
                    <label>
                        <input type="checkbox" class="change-status" id="status_' . $row->id . '" name="status_' . $row->id . '" data-url="' . $url . '" data-table="' . $table . '" value="' . $row->id . '"  checked>
                        <span></span>
                    </label>
                </span>
                </div>';
        } else {
            $statusHtml = '
                    <div class="text-center">
                        <span class="switch switch-icon switch-md">
                            <label>
                                <input type="checkbox" class="change-status" id="status_' . $row->id . '" name="status_' . $row->id . '" data-url="' . $url . '" value="' . $row->id . '" data-table="' . $table . '">
                                <span></span>
                            </label>
                        </span>
                    </div>';
        }
        return $statusHtml;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $userTypeFilter = request()->get("userTypeFilter", false);
        $rolefilter = request()->get("rolefilter", false);
        // DB::statement(DB::raw('set @rownum=0'));
        $fields = [
            // DB::raw('@rownum := @rownum + 1 as rownum'),
            DB::raw('CONCAT(first_name," ",last_name) as name'),
            "users.*", "roles.name as role", 'roles.id as role_id'
        ];

        $models = User::with(['getFullName', 'usersRole.roleName'])
            ->select($fields)
            ->leftjoin("role_users", function ($join) {
                $join->on("role_users.user_id", "=", "users.id");
            })
            ->leftjoin("roles", function ($join) {
                $join->on("role_users.role_id", "=", "roles.id");
            });

        if (request()->get('name', false)) {
            $models->whereHas('getFullName', function ($q) {
                $q->where(DB::raw('CONCAT(first_name," ",last_name)'), 'like', "%" . request()->get("name") . "%")
                    ->orWhere('first_name', 'like', "%" . request()->get("name") . "%")
                    ->orWhere('last_name', 'like', "%" . request()->get("name") . "%");
            });
        }
        if (request()->get('email', false)) {
            $models->where('email', 'like', "%" . request()->get("email") . "%");
        }
        if (request()->get('role', false)) {
            $models->whereHas('usersRole.roleName', function ($q) {
                $q->where('name', 'like', "%" . request()->get("role") . "%");
            });
        }
        if ($userTypeFilter != '') {
            $models->where('emp_type', $userTypeFilter);
        }
        if ($rolefilter != '') {
            $models->where('roles_id', $rolefilter);
        }
        $model->orderByDesc('id');
        return $this->applyScopes($models);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
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
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
