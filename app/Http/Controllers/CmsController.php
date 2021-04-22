<?php

namespace App\Http\Controllers;

use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CmsRequest;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Cms $cms
     * @return Response
     */
    public function index(Cms $cms)
    {
        return view('cms.index', ['cmss' => $cms->paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Cms $cms
     * @return Response
     */
    public function edit(Cms $cms)
    {
        return view('cms.edit', compact('cms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CmsRequest $request
     * @param Cms $cms
     * @return Response
     */
    public function update(CmsRequest $request, Cms $cms)
    {
        $cms->update(
            $request->merge(['password' => $request->get('password')])
                ->except(
                    [$request->get('password') ? '' : 'password']
                )
        );

        flash()->success('CMS Updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cms $cms
     * @return Response
     */
    public function destroy(Cms $cms)
    {
        $cms->delete();
        flash()->success('CMS Deleted!');
        return redirect()->route('cms.index');
    }
}
