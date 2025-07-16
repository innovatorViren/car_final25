<?php

namespace App\DataTables;

use App\Models\City;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Sentinel;
class CityDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
        ->eloquent($query)
        ->addColumn('action', function ($row) {
            return $this->checkrights($row);
        })
        ->editColumn('is_active', function ($row) {
            return getStatusHtml($row);
        })
        ->rawColumns(['action', 'is_active']);
    }
    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $editUrl = route('city.edit', [$row->id]);
        $deleteUrl = route('city.destroy', [$row->id]);

        if ($user->hasAnyAccess(['users.info', 'city.edit', 'city.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center">
                        <div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions">
                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor"></i>
                        </a>
                        <div class="dropdown-menu m-0 dropdown-menu-left" style="">
                            <ul class="navi navi-hover">';
        }

        if ($user->hasAnyAccess(['city.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editUrl . '" data-toggle="modal" data-target-modal="#commonModalID"  data-url="' . $editUrl . '" class="call-modal navi-link">' .
            '<span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span>' .
            '</a></li>';
        }

        if ($user->hasAnyAccess(['city.delete', 'users.superadmin'])) {
        $menu .= '<li class="navi-item"><a href="' . $deleteUrl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link">' .
            '<span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span>' .
            '</a></li>';
        }
        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }
        if ($user->hasAnyAccess(['users.info', 'city.edit','state.edit','state.delete', 'country.delete', 'users.superadmin'])) {
            $menu .= "</ul></div></div></td>";
        }

        return $menu;
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(City $model): QueryBuilder
    {
        $fields = ['cities.*','states.name as state_name','countries.name as country_name'];
        $models = City::select($fields)
            ->join('states', function($join){
                $join->on('cities.state_id', '=', 'states.id');
            })->join('countries', function($join){
                $join->on('cities.country_id', '=', 'countries.id');
            });

        if (request()->get('country_name')) {
            $models->where('countries.name', 'like', "%" . request()->get("country_name") . "%");
        }
        if (request()->get('state_name')) {
            $models->where('states.name', 'like', "%" . request()->get("state_name") . "%");
        }
        if (request()->get('name')) {
            $models->where('cities.name', 'like', "%" . request()->get("name") . "%");
        }

        return $this->applyScopes($models);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->parameters(['searching' => false, 'dom' => '<"wrapper"B>lfrtip', 'buttons' => ['excel', 'pdf'],])
        ->columns($this->getColumns())
        ->ajax('');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            ['name' => 'City', 'data' => 'City', 'title' => trans("comman.city")],
            ['data' => 'action', 'name' => 'action', 'title' => trans("comman.action"), 'render' => null, 'orderable' => false, 'searchable' => false, 'exportable' => false, 'printable' => false, 'footer' => '', 'width' => '80px'],
        ];
    }
    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'City';
    }
}
