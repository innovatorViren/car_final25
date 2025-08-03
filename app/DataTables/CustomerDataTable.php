<?php

namespace App\DataTables;

use App\Models\{Customer, CustomerAddress, Employee};
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;

class CustomerDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
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
            ->editColumn('first_name', function ($row) {
                $copyHtml = ' <a href="javascript:void(0)"
                class="btn btn-hover-light-primary btn-sm btn-icon copy-btn">
                <i class="fas fa-copy"></i></a> ';
                $user = Sentinel::getUser();
                if ($user->hasAnyAccess(['customers.view', 'users.superadmin'])) {
                    return '<a href="' . route('customers.show', [$row->id]) . '"  class="navi-link" target="_blank">' .
                        '  <span class="navi-text cust-text">' . $row->first_name . '</span></a> ' . $copyHtml;
                } else {
                    return $row->first_name;

                }
            })
            ->editColumn('is_active',function ($row) {
                return getStatusHtml($row,'customers.edit');
            })

            ->rawColumns([
                'action',
                'first_name',
                'is_active',
            ]);
    }

    public function checkrights($row)
    {
        // $user = Sentinel::getUser();
        $user = Sentinel::getUser();
        $menu = '';
        $editurl = route('customers.edit', [$row->id]);
        $deleteurl = route('customers.destroy', [$row->id]);

        if ($user->hasAnyAccess(['users.info', 'customers.edit', 'customers.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center"><div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions"><a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-bold-more-hor"></i></a><div class="dropdown-menu m-0 dropdown-menu-right" style=""><ul class="navi navi-hover">';
        }

        if ($user->hasAnyAccess(['customers.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editurl . '"  class="navi-link"><span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['customers.delete', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $deleteurl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link"><span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }

        if ($user->hasAnyAccess(['users.info', 'customers.edit', 'customers.delete', 'users.superadmin'])) {
            $menu .= "</ul></div></div></td>";
        }

        return $menu;
    }

    public function query()
    {
        $user = Sentinel::getUser();
        $login_user_id = $user->id ?? '';

        $fields = [
            'customers.id as id',
            'customers.first_name as first_name',
            'customers.middle_name as middle_name',
            'customers.last_name as last_name',
            'customers.mobile as mobile',
            'customers.email as email',
            'customers.is_active'
        ];
        $model = Customer::select($fields);

        if (request()->get('first_name', false)) {
            $model->where('first_name', 'like', "%" . request()->get("first_name") . "%");
        }
        if (request()->get('middle_name', false)) {
            $model->where('customers.middle_name', 'like', "%" . request()->get("middle_name") . "%");
        }
        if (request()->get('last_name', false)) {
            $model->where('customers.last_name', 'like', "%" . request()->get("last_name") . "%");
        }
        if (request()->get('email', false)) {
            $model->where('customers.email', 'like', "%" . request()->get("email") . "%");
        }
        if (request()->get('mobile', false)) {
            $model->where('customers.mobile', 'like', "%" . request()->get("mobile") . "%");
        }

        return $this->applyScopes($model);
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
            Column::make('person_name'),
            Column::make('mobile'),
            Column::make('is_active'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    // protected function filename(): string
    // {
    //     return 'AccountMaster_' . date('YmdHis');
    // }
}
