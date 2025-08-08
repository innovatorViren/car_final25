<?php

namespace Mahalaxmi\Services;

/**
 * This class is onzup service for Permissions.
 */
class Permission
{
    private $modules = [
        // Side Panel
        'users' => [
            'users.list',
            'users.view',
            'users.add',
            'users.edit',
            'users.autologin',
            'users.info',
            'users.superadmin',
        ],
        'roles' => [
            'roles.list',
            'roles.add',
            'roles.edit',
            'roles.delete',
        ],

        'department' => [
            'department.list',
            'department.view',
            'department.add',
            // 'department.edit',
            // 'department.delete',
        ],
        'designation' => [
            'designation.list',
            'designation.view',
            'designation.add',
            'designation.edit',
            // 'designation.delete',
        ],
        'country' => [
            'country.list',
            'country.add',
            'country.edit',
            'country.delete',
        ],
        'state' => [
            'state.list',
            'state.add',
            'state.edit',
            'state.delete',
        ],
        'city' => [
            'city.list',
            'city.add',
            'city.edit',
            'city.delete',
        ],
        'car_brand' => [
            'car_brand.list',
            'car_brand.add',
            'car_brand.edit',
            'car_brand.delete',
        ],
        'car_model' => [
            'car_model.list',
            'car_model.add',
            'car_model.edit',
            'car_model.delete',
        ],
        'years' => [
            'years.list',
            // 'years.view',
            'years.add',
            'years.edit',
            // 'years.delete',
        ],
        'SMTP_configuration' => [
            'SMTP_configuration.list',
            // 'SMTP_configuration.view',
            'SMTP_configuration.add',
            'SMTP_configuration.edit',
            'SMTP_configuration.delete',
        ],
        'mail_template' => [
            'mail_template.list',
            // 'mail_template.view',
            'mail_template.add',
            'mail_template.edit',
            'mail_template.delete',
        ],
        'banner' => [
            'banner.list',
            // 'banner.view',
            'banner.add',
            'banner.edit',
            'banner.delete',
        ],

        'customers' => [
            'customers.list',
            'customers.view',
            'customers.add',
            'customers.edit',
            'customers.delete',
        ],
        //Employee (Menu)
        'employee' => [
            'employee.list',
            'employee.view',
            'employee.add',
            'employee.edit',
            'employee.delete',
        ],

        //Plan (Menu)
        'plan' => [
            'plan.list',
            'plan.view',
            'plan.add',
            'plan.edit',
            'plan.delete',
            'plan.assign_car',
        ],

        /* don't remove this */
    ];
    public function getPermissions()
    {
        return $this->modules;
    }
}
