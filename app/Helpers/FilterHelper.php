<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class FilterHelper
{
    public static function applyFilter(Builder $query, array $filters): Builder
    {
        foreach ($filters as $key => $filter) {
            if(in_array($key, ['page', 'limit', 'sort', 'order', 'perPage', 'sortField', 'sortOrder'])) {
                continue;
            }
            
            $type = $filter['type'] ?? 'text';
            $field = $filter['field'];
            $op = $filter['operator'] ?? '=';
            $value = $filter['value'];
            
            if ($type !== 'funnel' &&($value === null || $value === '')) {
                continue;
            }
            
            $query = match ($type) {
                
                'text' => self::applyTextFilter($query, $filter),
                
                'funnel' => self::applyFunnelFilter($query, $filter),
                
                'number' => self::applyNumberFilter($query, $field, $op, $value),
                
                default => $query->where($field, $op, $value),
            };
        }
        
        return $query;
    }
    
    private static function applyTextFilter(Builder $query, array $filter): Builder
    {
        if(!empty($filter['relation'])) {
            $query->whereHas($filter['relation'], function(Builder $q) use ($filter) {
                $q->where($filter['field'], $filter['operator'], '%'.$filter['value'].'%');
            });
        } else {
            $query->where($filter['field'], $filter['operator'], '%'.$filter['value'].'%');
        }
        return $query;
    }
    
    private static function applyFunnelFilter(Builder $query, array $filter): Builder
    {
        $checkedOptions = collect($filter['options'])
            ->filter(fn ($option) => $option['checked'] || $option['checked'] === 'true')
            ->values();
        
        $query = $query->where(function ($query) use ($checkedOptions, $filter) {
        
            foreach ($checkedOptions as $key=>$option) {
                $method = 'orWhere';
                if($key === 0){
                    $method = 'where';
                }
                
                if(!empty($filter['relation'])) {
                    $method .= 'Has';
                    $query->{$method}($filter['relation'], function($q) use($option, $filter) {
                        $q->where($filter['field'], $filter['operator'], $option['value']);
                    });
                }else {
                    $query->{$method}($filter['field'], $filter['operator'],  $option['value']);
                }
            }
        });
        
        return $query;
    }
    
    private static function applyNumberFilter(Builder $query, array $filter): Builder
    {
        if(!empty($filter['relation'])) {
            $query = $query->whereHas($filter['relation'], function(Builder $q) use ($filter) {
                $q->where($filter['field'], $filter['operator'], '%'.$filter['value'].'%');
            });
        } else {
            $query->where($filter['field'], $filter['operator'], '%'.$filter['value'].'%');
        }
        return $query;
    }

}