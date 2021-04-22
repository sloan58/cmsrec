<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cms;
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
        return view('cms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CmsRequest $request
     * @return void
     */
    public function store(CmsRequest $request)
    {
        try {
            Cms::create($request->all());
            flash()->success('CMS Create!');
            return redirect()->route('cms.index');
        } catch (Exception $e) {
            flash()->error("Could not create CMS server ({$e->getMessage()})")->important();
            return back()->withInput();
        }
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
