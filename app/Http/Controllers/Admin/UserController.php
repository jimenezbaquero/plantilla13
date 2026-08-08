<?php

namespace App\Http\Controllers\Admin;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Presenters\UserPresenter;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends \App\Http\Controllers\UserController
{
    public function index(Request $request){
        $this->component = 'User/Admin/Index';
        $this->actions = ['edit','delete'];
        $this->filters = UserPresenter::filters();
        $this->columns = UserPresenter::columns();
        return parent::index($request);
    }
    
}
