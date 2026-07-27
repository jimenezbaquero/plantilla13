<?php

namespace App\Http\Controllers;

use App\Presenters\UserPresenter;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(
        private UserService $service
    ) {
    }

    public function index(Request $request){
        return Inertia::render($this->getComponent(), [
            'users' => $this->service->getData($request->all()),
            'filters' => UserPresenter::filters(),
            'columns' => UserPresenter::columns(),
            'actions' => $this->getActions(),
        ]);
    }

    public function getData(Request $request){
        return $this->service->getData($request->all());
    }
}
