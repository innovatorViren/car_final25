<?php

namespace App\DataTables;

use App\Models\MailTemplate;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;


class MailTemplateDatatable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                return $this->checkrights($row);
            })->editColumn('module_name', function ($row) {
                $user = Sentinel::getUser();
                if ($user->hasAnyAccess(['configuration.view', 'users.superadmin'])) {
                    return $row->module_name ?? '-';
                } else {
                    return '-';
                }
            })
            ->editColumn('is_active', function ($row) {
                return getStatusHtml($row);
            })
            ->rawColumns(['action', 'is_active', 'module_name']);
    }

    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $editUrl = route('mail-template.edit', [$row->id]);
        $deleteUrl = route('mail-template.destroy', [$row->id]);

        if ($user->hasAnyAccess(['users.info', 'mail_template.edit', 'mail_template.delete', 'users.superadmin'])) {
            $menu .= '<td class="text-center"><div class="dropdown dropdown-inline text-center" title="" data-placement="left" data-original-title="Quick actions"><a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ki ki-bold-more-hor"></i></a><div class="dropdown-menu m-0 dropdown-menu-left" style=""><ul class="navi navi-hover">';
        }

        if ($user->hasAnyAccess(['mail_template.edit', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $editUrl . '" data-toggle="modal" data-target-modal="#commonModalID"  data-url="' . $editUrl . '" class="call-modal navi-link"><span class="navi-icon"><i class="fas fa-edit"></i></span><span class="navi-text">' . __('common.edit') . '</span></a></li>';
        }

        if ($user->hasAnyAccess(['mail_template.delete', 'users.superadmin'])) {
            $menu .= '<li class="navi-item"><a href="' . $deleteUrl . '" data-id="' . $row->id . '" data-table="dataTableBuilder" class="delete-confrim navi-link"><span class="navi-icon"><i class="fas fa-trash-alt"></i></span><span class="navi-text">' . __('common.delete') . '</span></a></li>';
        }
        if ($user->hasAnyAccess(['users.info', 'users.superadmin'])) {
            $menu .= getInfoHtml($row);
        }
        if ($user->hasAnyAccess(
            [
                'users.info',
                'mail_template.edit',
                'mail_template.delete',
                'users.superadmin'
            ]
        )) {
            $menu .= "</ul></div></div></td>";
        }

        return $menu;
    }

    public function query()
    {
        $fields = [
            'mail_templates.id',
            DB::raw("(CASE WHEN mail_templates.module_name !='' THEN  REPLACE(mail_templates.module_name, '_', ' ') ELSE '-' END) as module_name"),
            'smtp_configurations.username',
            'mail_templates.subject',
            'mail_templates.message_body',
            'mail_templates.is_active',
        ];

        $models = MailTemplate::select($fields)->join('smtp_configurations', function ($join) {
            $join->on('smtp_configurations.id', '=', 'mail_templates.smtp_id');
        });

        $module_name = strtolower(str_replace(' ', '_', request()->get('module_name')));
        if ($module_name) {
            $models->where('mail_templates.module_name', 'like', "%" . $module_name . "%");
        }

        $filter_smtp = request()->get('filter_smtp');
        if ($filter_smtp) {
            $models->where('smtp_configurations.username', 'like', "%" . $filter_smtp . "%");
        }

        $filter_subject = request()->get('filter_subject');
        if ($filter_subject) {
            $models->where('mail_templates.subject', 'like', "%" . $filter_subject . "%");
        }

        return $this->applyScopes($models);
    }

    public function html()
    {
        return $this->builder()
            ->parameters(['searching' => false, 'dom' => '<"wrapper"B>lfrtip', 'buttons' => ['excel', 'pdf'],])
            ->columns($this->getColumns())
            ->ajax('');
    }

    private function getColumns()
    {
        return [
            ['name' => 'module_name', 'data' => 'module_name', 'title' => trans("smtp_configuration.module_name")],
            ['data' => 'action', 'name' => 'action', 'title' => trans("comman.action"), 'render' => null, 'orderable' => false, 'searchable' => false, 'exportable' => false, 'printable' => false, 'footer' => '', 'width' => '80px'],
        ];
    }

    protected function filename(): string
    {
        return 'mail_template';
    }
}
