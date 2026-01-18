<?php

namespace App\DataTables;

use App\Models\{Order,Wash};
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use DB;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return $this->checkrights($row);
            })
            ->editColumn('pay_amount', function ($row) {
                return $row->pay_amount;
                // return format_amount($row->pay_amount,2);
            })
            ->editColumn('start_date', function ($row) {
                return custom_date_format($row->start_date, 'd-m-Y');
            })
            ->editColumn('end_date', function ($row) {
                return custom_date_format($row->end_date, 'd-m-Y');
            })
            ->editColumn('code', function ($row) {
                return '<a href="' .route('orders.show', [$row->id]) . '"  class="navi-link">' .
                                '<span class="navi-text">' .$row->code . '</span>' .
                        '</a>';
            })
            ->editColumn('status', function ($row) {
               if ($row->status == 'Pending') {
                    return '<span class="label label-lg label-light-warning font-weight-bolder label-inline">' . $row->status . '</span>';
                } else if ($row->status == 'Partial') {
                    return '<span class="badge text-blue font-weight-bolder">' . $row->status . '</span>';
                } else if ($row->status == 'Completed') {
                    return '<span class="label label-lg label-light-success font-weight-bolder label-inline">' . $row->status . '</span>';
                } else {
                    return '<span class="label label-lg label-light-danger font-weight-bolder label-inline">' . $row->status . '</span>';
                }
            })
            ->editColumn('customer_name', function ($row) {
                return $row->customer_name ?? '-';
            })
            ->rawColumns(['action','pay_amount', 'order_date', 'code','status','customer_name']);
    }

    public function checkrights($row)
    {
        // $user = Sentinel::getUser();
        $user = Sentinel::getUser();
        $menu = '';
        $viewurl = route('orders.show', [$row->id]);
        $deleteurl = route('orders.destroy', [$row->id]);

        if ($user->hasAnyAccess(['users.info','orders.view', 'orders.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center"><div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions"><a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-bold-more-hor"></i></a><div class="dropdown-menu m-0 dropdown-menu-right" style=""><ul class="navi navi-hover">';
        }

        if ($user->hasAnyAccess(['orders.view', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $viewurl . '"  class="navi-link"><span class="navi-icon"><i class="fas fa-eye"></i></span><span class="navi-text">' . __('common.view') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['orders.delete', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $deleteurl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link"><span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }

        if ($user->hasAnyAccess(['users.info', 'orders.delete', 'users.superadmin'])) {
            $menu .= "</ul></div></div></td>";
        }

        return $menu;
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        $request = request();
        $datefilter = $request->get('datefilter', false);
        $customerFilter = $request->get('customerFilter', false);


        $select =[
            'orders.id',
            'orders.code',
            'orders.date',
            'orders.start_date',
            'orders.end_date',
            'orders.frequency_type',
            'orders.price',
            'orders.pay_amount',
            'orders.status',
            DB::raw('CONCAT(customers.first_name," ",customers.last_name) as customer_name'),
            'car_models.name as car_model_name',
        ];
        $model = Order::join('customers', 'customers.id', '=', 'orders.customer_id')
            ->join('car_models', 'car_models.id', 'orders.car_model_id')
            ->select($select);

        // if(!empty($datefilter))
        // {
        //     $dateArr = explode(' | ', $datefilter);
        //     $fromDate = date('Y-m-d', strtotime($dateArr[0]));
        //     $toDate = (!empty($dateArr[1])) ? date('Y-m-d', strtotime($dateArr[1])) : date('Y-m-d', strtotime($dateArr[0]));
        //     $model->whereBetween('shop_orders.order_date',[$fromDate,$toDate]);
        // }
        if ($customerFilter) {
            $model->where('customers.id', $customerFilter);
        }

            // dd($model->get());

        return $this->applyScopes($model->newQuery());
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
                        Button::make('create'),
                        Button::make('export'),
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
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
