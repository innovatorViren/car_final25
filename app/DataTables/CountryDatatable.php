<?php

namespace App\DataTables;

use App\Models\Country;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Sentinel;
use Localization;
use Illuminate\Support\Facades\DB;


class CountryDatatable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataTable($query)
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
        $editUrl = route('country.edit', [$row->id]);
        $deleteUrl = route('country.destroy', [$row->id]);

        if ($user->hasAnyAccess(['users.info', 'country.edit', 'country.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center">
                        <div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions">
                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor"></i>
                        </a>
                        <div class="dropdown-menu m-0 dropdown-menu-left" style="">
                            <ul class="navi navi-hover">';
        }

        if ($user->hasAnyAccess(['country.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editUrl . '" data-toggle="modal" data-target-modal="#commonModalID"  data-url="' . $editUrl . '" class="call-modal navi-link">' .
            '<span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span>' .
            '</a></li>';
        }

        if ($user->hasAnyAccess(['country.delete', 'users.superadmin'])) {
        $menu .= '<li class="navi-item"><a href="' . $deleteUrl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link">' .
            '<span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span>' .
            '</a></li>';
        }
        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }
        if ($user->hasAnyAccess(['users.info', 'country.edit', 'country.delete', 'users.superadmin'])) {
            $menu .= "</ul></div></div></td>";
        }

        return $menu;
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $models = Country::withCount('states')->with(['createdBy']);

        if (request()->get('name')) {
            $models->where('name', 'like', "%" . request()->get("name") . "%");
        }

        return $this->applyScopes($models);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
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
    private function getColumns()
    {
        return [
            ['name' => 'country', 'data' => 'country', 'title' => trans("comman.country")],
            ['data' => 'action', 'name' => 'action', 'title' => trans("comman.action"), 'render' => null, 'orderable' => false, 'searchable' => false, 'exportable' => false, 'printable' => false, 'footer' => '', 'width' => '80px'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'Country';
    }
}
