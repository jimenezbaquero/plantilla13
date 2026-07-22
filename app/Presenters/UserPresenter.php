<?php

namespace App\Presenters;

class UserPresenter
{
    public static function columns(): array
    {
        return [

            'name' => [
                'key' => 'name',
                'field' => 'name',
                'header' => 'users.name',
                'sortable' => true,
                'filterable' => true,
                'type' => 'text',
                'width' => 'auto',
            ],
            'role' => [
                'key' => 'role',
                'field' => 'role',
                'header' => 'users.role',
                'sortable' => true,
                'filterable' => true,
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
                'type' => 'text',
                'field' => 'name',
                'operator' => 'like',
                'order_direction' => '',
            ]
        ];
    }
}
