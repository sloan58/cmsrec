<?php

namespace App\Http\Controllers;

use App\Models\CmsCoSpace;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class CoSpaceController extends Controller
{
    /**
     * Display a listing of the CmsCoSpaces
     * @return Factory|View
     */
    public function index()
    {
        $coSpaces = CmsCoSpace::all();

        return view('cospaces.index', compact('coSpaces'));
    }
}
