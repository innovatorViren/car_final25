<?php

namespace App\DataTables;

use App\Models\Banner;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class BannerDataTable extends DataTable
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
            ->editColumn('image', function ($row) {
                return "<a href='" . asset($row->image) . "' target='_blank'><img src='" . asset($row->image) . "' class='img-fluid' width='120px'></a>";
            })
            ->editColumn('title', function ($row) {
                return $row->title ?? '';
            })
            ->editColumn('is_active', function ($row) {
                return getStatusHtml($row);
            })
            ->rawColumns(['action', 'is_active', 'title', 'image']);
    }
    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $editUrl = route('banner.edit', [$row->id]);
        $deleteUrl = route('banner.destroy', [$row->id]);
        $delete_reason = route('delete-reason', [$row->id, 'banner']);

        if ($user->hasAnyAccess(['users.info', 'banner.edit', 'banner.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center">
                        <div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions">
                        <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor"></i>
                        </a>
                        <div class="dropdown-menu m-0 dropdown-menu-left" style="">
                            <ul class="navi navi-hover">';
        }

        if ($user->hasAnyAccess(['banner.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editUrl . '" data-toggle="modal" data-target-modal="#commonModalID"  data-url="' . $editUrl . '" class="call-modal navi-link">' .
                '<span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span>' .
                '</a></li>';
        }

        if ($user->hasAnyAccess(['banner.delete', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $deleteUrl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="call-modal navi-link" data-target-modal="#commonModalID" data-id="{{ $row->id }}" data-toggle="modal" data-url="' . $delete_reason . '">' .
                '<span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span>' .
                '</a></li>';
        }
        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }
        if ($user->hasAnyAccess(['users.info', 'banner.edit', 'state.edit', 'state.delete', 'country.delete', 'users.superadmin'])) {
            $menu .= "</ul></div></div></td>";
        }

        return $menu;
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        $model = Banner::query();
        $filter_title = request()->get('filter_title', false);

        if ($filter_title) {
            $model->where('title', 'like', "%" . $filter_title . "%");
        }

        return $model;
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
            //     
        ];
    }
    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Banner';
    }
}
