<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderHelper
{
    public static function makeOrder($query, $payload)
    {
        foreach ($payload as $key => $item) {
            
            if (in_array($key, [
                'page',
                'limit',
                'sort',
                'order',
                'perPage',
                'sortField',
                'sortOrder',
            ])) {
                continue;
            }
            
            if (empty($item['order_direction'])) {
                continue;
            }
            
            $orderField = $item['order_field']?? $item['field'];
            
            if (empty($item['relation'])) {
                
                $query->orderBy(
                    $orderField,
                    $item['order_direction']
                );
                
                continue;
            }
            
            $relation = $query->getModel()->{$item['relation']}();
            
            if ($relation instanceof BelongsTo || $relation instanceof HasOne) {
                
                $query->withAggregate(
                    $item['relation'],
                    $orderField
                );
                
                $query->orderBy(
                    $item['relation'] . '_' . $orderField . '_aggregate',
                    $item['order_direction']
                );
                
            } elseif ($relation instanceof HasMany || $relation instanceof BelongsToMany) {
                
                $aggregate = $item['order_direction'] === 'asc'
                    ? 'min'
                    : 'max';
                
                $query->{"with" . ucfirst($aggregate)}(
                    $item['relation'],
                    $orderField
                );
                
                $query->orderBy(
                    $item['relation'] . '_' . $aggregate . '_' . $orderField,
                    $item['order_direction']
                );
            }
        }
        
        return $query;
    }
}