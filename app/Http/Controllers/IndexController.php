<?php

namespace App\Http\Controllers;

use App\Models\App;

class IndexController extends Controller
{
    public function index()
    {
        $apps = App::query()->orderBy('position')->get(['id', 'name']);
        return view('index', compact('apps'));
    }
}