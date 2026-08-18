<?php

namespace App\Helpers;

use Spatie\Permission\Models\Role;

class FunnelHelper
{
    public static function getOptions($field) {
        $options = [];
        switch ($field) {
            case('is_active'):
                $options = self::createBooleanOptions();
                break;
            case('role'):
                $options = self::createRoleOptions();
                break;
        }
        return $options;
    }
    
    private static function createBooleanOptions() {
        return [
            'yes' => [
                'label' => __('app.yes'),
                'value' => 1,
                'checked' => false
            ],
            'no' => [
                'label' => __('app.no'),
                'value' => 0,
                'checked' => false
            ]
        ];
    }
    
    private static function makeOptions($options){
        $data = [];
        foreach ($options as $key=>$option) {
            $data[$key] = [
                'label' => $option,
                'value' => $key,
                'checked' => false,
            ];
        }
        return $data;
    }
    
    private static function createRoleOptions() {
        $roles = Role::all()->pluck('name', 'id')->toArray();
        return self::makeOptions($roles);
    }
}