<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function index()
    {
        return api_resource('Category')->collection(collect(config('event.categories')));
    }
}
