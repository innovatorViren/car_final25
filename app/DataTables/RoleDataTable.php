<?php

namespace App\DataTables;

use App\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class RoleDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('users', function ($row) {
                // return $row->users->count();
                $count = 0;
                foreach ($row->users as $user) {
                    $user = Sentinel::findById($user->id);
                    if (Activation::completed($user)) {
                        $count++;
                    }
                }
                if ($count > 0) {
                    return '<a href="#" class="roleModal" data-toggle="modal" data-target-modal="#roleModal" data-footer-hide="true" data-id="' . $row->id . '">' . $count . '</a>';
                }
                return $count;
                //return $row->users;
            })
            ->addColumn('action', function ($row) {
                return $this->checkrights($row);
            })
            // ->addColumn('name', function ($row) {
            //     $user = Sentinel::getUser();
            //     if ($user->hasAnyAccess(['roles.edit', 'users.superadmin'])) {
            //         return '<a href="' . route('roles.edit', [$row->id]) . '"  class="navi-link">' .
            //             '<span class="navi-text">' . $row->name . '</span>' .
            //             '</a>';
            //     } else {
            //         return $row->name;
            //     }
            // })
            ->rawColumns(
                [
                    'users',
                    'action'
                ]
            );
    }

    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $editurl = route('roles.edit', [$row->id]);
        $deleteurl = route('roles.destroy', [$row->id]);

        $menu .= '<td class="text-center"><div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions"><a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-bold-more-hor"></i></a><div class="dropdown-menu m-0 dropdown-menu-right" style=""><ul class="navi navi-hover">';

        // if ($user->hasAnyAccess(['roles.info', 'users.superadmin'])) {
        //     $menu .= getInfoHtml($row);
        // }

        if ($user->hasAnyAccess(['roles.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editurl . '" class="navi-link" data-show-edit-model ="yes"><span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['roles.delete', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $deleteurl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link"><span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span></a></li>';
        }

        $menu .= "</ul></div></div></td>";
        return $menu;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Role $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Role $model)
    {
        //return $model->newQuery();
        $model = Role::select();

        if (request()->get('name', false)) {
            $model->where('name', 'like', "%" . request()->get("name") . "%");
        }

        if (request()->get('slug', false)) {
            $model->where('slug', 'like', "%" . request()->get("slug") . "%");
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
            ->setTableId('role-table')
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
        return 'Role_' . date('YmdHis');
    }
}
