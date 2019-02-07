<?php

namespace Modules\Event\Http\Controllers;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return api_resource('Category')->collection(collect(config('event.categories')));
    }
}
