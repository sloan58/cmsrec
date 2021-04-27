<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class CmsCoSpaceController extends Controller
{
    /**
     * Display a listing of the CmsCoSpaces
     * @return Factory|View
     */
    public function index()
    {
        return view('cospaces.index');
    }
}
