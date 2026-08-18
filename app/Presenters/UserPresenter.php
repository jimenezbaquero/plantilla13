<?php

namespace App\Presenters;

use App\Helpers\FunnelHelper;

class UserPresenter
{
    public static function columns(): array
    {
        return [

            'name' => [
                'key' => 'name',
                'field' => 'name',
                'header' => 'users.fields.name',
                'sortable' => true,
                'filterable' => true,
                'type' => 'text',
                'width' => 'auto',
            ],
            'role' => [
                'key' => 'role',
                'field' => 'role',
                'header' => 'users.fields.role',
                'sortable' => true,
                'filterable' => false,
                'type' => 'text',
                'width' => '200px',
            ],
        ];
    }

    public static function filters(): array
    {
        return [
            'name' => [
                'value' => '',
                'type' => 'text',
                'field' => 'name',
                'operator' => 'like',
                'order_direction' => '',
            ],
            'role' => [
                'value' => '',
                'type' => 'funnel',
                'field' => 'id',
                'relation' => 'roles',
                'operator' => '=',
                'order_direction' => '',
                'order_field' => 'name',
                'showFunnel' => false,
                'options' => FunnelHelper::getOptions('role'),
            ]
        ];
    }
}
