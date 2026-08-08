<?php

namespace App\Http\Controllers;

use LogicException;

abstract class Controller
{
    protected ?string $component = null;
    protected ?array $actions = [];
    protected ?array $filters = [];
    protected ?array $columns = [];
    
    protected function getComponent(): string
    {
        if ($this->component === null) {
            throw new LogicException(
                'No se ha definido el componente para ' . static::class
            );
        }
        
        return $this->component;
    }
    
    protected function getActions(): array
    {
        return $this->actions;
    }
    
    protected function getFilters(): array
    {
        return $this->filters;
    }
    
    protected function getColumns(): array
    {
        return $this->columns;
    }
}
