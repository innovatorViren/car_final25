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
            // ->addColumn('action', function ($row) {
            //     return $this->checkrights($row);
            // })
            ->editColumn('rownum', function ($row) {
                return $this->i++;
            })
            ->editColumn('company_name', function ($row) {
                $copyHtml = ' <a href="javascript:void(0)"
                class="btn btn-hover-light-primary btn-sm btn-icon copy-btn">
                <i class="fas fa-copy"></i></a> ';
                $user = Sentinel::getUser();
                if ($user->hasAnyAccess(['customers.view', 'users.superadmin'])) {
                    return '<a href="' . route('customers.show', [$row->id]) . '"  class="navi-link" target="_blank">' .
                        '  <span class="navi-text cust-text">' . $row->company_name . '</span></a> ' . $copyHtml;
                        // return '<a href="' . route('customers.show', [$row->id]) . '"  class="navi-link" target="_blank">' .
                        // '  <span class="navi-text cust-text name_ellipsis_modual mt-10px" data-toggle="tooltip" data-placement="top" title="'.$row->company_name.'">' . $row->company_name . '</span></a> ' . $copyHtml;
                } else {
                    return $row->company_name;//'<span class="navi-text cust-text name_ellipsis_modual mt-10px" data-toggle="tooltip" data-placement="top" title="'.$row->company_name.'">' . $row->company_name . '</span>';

                } // return '<a href="' . route('customers.show', [$row->id]) . '"  class="navi-link" target="_blank">' .
                // '  <span c
                if ($user->hasAnyAccess(['customers.view', 'users.superadmin'])) {
                    return '<a href="' . route('customers.show', [$row->id]) . '"  class="navi-link" target="_blank">' .
                        '  <span class="navi-text cust-text name_ellipsis_modual mt-10px" data-toggle="tooltip" data-placement="top" title="'.$row->company_name.'">' . $row->company_name . '</span></a> ' . $copyHtml;
                } else {
                    return  '<span class="navi-text cust-text name_ellipsis_modual mt-10px" data-toggle="tooltip" data-placement="top" title="'.$row->company_name.'">' . $row->company_name . '</span>';
                }
            })
            ->editColumn('is_active',function ($row) {
                return getStatusHtml($row,'customers.edit');
            })

            ->rawColumns([
                // 'action',
                'company_name',
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
        $primaryCustomer = Customer::where('managed_by', $user->id)->count();
        $empData = Employee::where('id', $user->emp_id)->first();
        $cheack = $empData->department_id ?? '';
        $login_user_id = $user->id ?? '';

        $type_filter = request()->get("type_filter", false);
        $gstTypeFilter = request()->get("gstTypeFilter", false);
        $gst_type = request()->get("gst_type", false);
        $pan_no = request()->get("pan_no", false);

        // rownum
        // DB::statement(DB::raw('set @rownum=0'));
        $fields = [
            DB::raw('@rownum := @rownum as rownum'),
            'customers.id as id',
            'customers.company_name as company_name',
            'customers.person_name as person_name',
            'customers.mobile as mobile',
            'customers.email as email',
            'customers.gst_type as gst_type',
            'customers.gst_no as gst_no',
            'customers.pan_no as pan_no',
            'states.name as state',
            'cities.name as city',
            'customer_addresses.pincode as pincode',
            'employees.first_name',
            'employees.last_name',
            'customers.is_active',
            'customers.created_by',
            'customers.created_at',
            'customers.updated_by',
            'customers.updated_at',
            'customers.credit_days',
            'customers.credit_limit',
        ];
        $model = Customer::select($fields)
            ->leftJoin('customer_addresses', function ($join) {
                $join->on('customer_addresses.customer_id', '=', 'customers.id');
                // $join->where('customer_addresses.address_type', '=', 'office');
            })
            ->leftJoin('customer_bank_details', 'customers.id', '=', 'customer_bank_details.customer_id')
            ->leftJoin('states', 'customer_addresses.state_id', '=', 'states.id')
            ->leftJoin('cities', 'customer_addresses.city_id', '=', 'cities.id')
            ->leftJoin('employees', 'customers.managed_by', '=', 'employees.id');

        if (request()->get('customerfilter') != '') {
            $companyName = request()->get('customerfilter');
            $model->where('customers.id', $companyName);
        }

        if (request()->get('statefilter') != '') {
            $model->where('states.id', 'like', "%" . request()->get('statefilter') . "%");
        }

        if ($gstTypeFilter != '') {
            $model->where('customers.gst_type', $gstTypeFilter);
        }

        if (request()->get('company_name', false)) {
            $model->where('company_name', 'like', "%" . request()->get("company_name") . "%");
        }
        if (request()->get('person_name', false)) {
            $model->where('customers.person_name', 'like', "%" . request()->get("person_name") . "%");
        }
        if (request()->get('email', false)) {
            $model->where('customers.email', 'like', "%" . request()->get("email") . "%");
        }
        if (request()->get('mobile', false)) {
            $model->where('customers.mobile', 'like', "%" . request()->get("mobile") . "%");
        }
        if (request()->get('pan_no', false)) {
            $model->where('customers.pan_no', 'like', "%" . request()->get("pan_no") . "%");
        }
        if (request()->get('gst_no', false)) {
            $model->where('customers.gst_no', 'like', "%" . request()->get("gst_no") . "%");
        }
        if (request()->get('state', false)) {
            $model->where('states.name', 'like', "%" . request()->get("state") . "%");
        }
        if (request()->get('city', false)) {
            $model->where('cities.name', 'like', "%" . request()->get("city") . "%");
        }
        if (request()->get('pincode', false)) {
            $model->where('customer_addresses.pincode', 'like', "%" . request()->get("pincode") . "%");
        }
        if (request()->get('credit_days', false)) {
            $model->where('customers.credit_days', 'like', "%" . request()->get("credit_days") . "%");
        }
        if (request()->get('credit_limit', false)) {
            $model->where('customers.credit_limit', 'like', "%" . request()->get("credit_limit") . "%");
        }
        if ($gst_type != '') {
            $model->where('customers.gst_type','like', "%" . $gst_type . "%");
        }

        if ($pan_no != '') {
            $model->where('customers.pan_no','like', "%" . $pan_no . "%");
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
