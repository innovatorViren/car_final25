<?php

namespace App\DataTables;

use App\Models\SalesReturn;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;

class SalesReturnDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('date', function ($row) {
                return custom_date_format($row->date, 'd-m-Y');
            })
            ->editColumn('total_qty', function ($row) {
                $totalQty = $row->total_qty;
                return numberFormatPrecision($totalQty, 0);
            })
            ->editColumn('customer_id', function ($row) {
                $customer = $row->customer->company_name ?? '';
                return $customer;
            })
            ->editColumn('code', function ($row) {
                $user = Sentinel::getUser();
                if ($user->hasAnyAccess(['sales_return.view', 'users.superadmin'])) {
                    return '<a href="' . route('sales-return.show', [$row->id]) . '"  class="navi-link">' .
                        '<span class="navi-text">' . $row->code . '</span>' .
                        '</a>';
                } else {
                    return $row->code;
                }
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'Partial') {
                    return '<span class="badge text-blue font-weight-bolder">' . $row->status . '</span>';
                } elseif ($row->status == 'Generated') {
                    return '<span class="label label-lg label-light-success font-weight-bolder label-inline">' . $row->status . '</span>';
                } else{
                    return '<span class="label label-lg label-light-warning font-weight-bolder label-inline">' . $row->status . '</span>';
                } 
            })
            ->rawColumns(['code', 'date', 'total_qty', 'customer_id', 'status']);
    }

    public function query(SalesReturn $model)
    {
        $request = request();
        $customerId = $request->customerId ?? '';

        $code = $request->code ?? '';
        $status = $request->status ?? '';
        $customer = $request->customer ?? '';
        $date = $request->date ?? '';

        $model = SalesReturn::with(['customer', 'items']);
        if($customerId > 0){
            $model->where('customer_id', $customerId);
        }
        if($code > 0){
            $model->where('code', 'like', "%" . $code . "%");
        }
        if($status > 0){
            $model->where('status', 'like', "%" . $status . "%");
        }
        if($customer > 0){
            $model->whereHas('customer', function($query) use ($customer) {
                $query->where('company_name', 'like', "%" . $customer . "%");
            });
        }
        if($date > 0){
            $model->where(DB::raw("DATE_FORMAT(date, '%d-%m-%Y')"), 'like', "%" . $date . "%");
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
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    protected function filename(): string
    {
        return 'SalesReturn_' . date('YmdHis');
    }
}
