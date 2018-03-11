<?php namespace AlertME\Http\Controllers\api;

use AlertME\DataSource;
use AlertME\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiDataSourceController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $datasources = DataSource::all();

        return response()->json(array(
            'error'       => false,
            'datasources' => $datasources->toArray()),
            200
        );
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
     * @return Response
     */
    public function store(Request $request)
    {
        $datasource = new DataSource;
        $datasource->title = $request->input('title', $datasource->title);
        $datasource->description = $request->input('desc', $datasource->description);
        $datasource->url = $request->input('url', $datasource->url);

        $datasource->save();

        return response()->json(array(
            'error'      => false,
            'datasource' => $datasource->toArray()),
            200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $datasource = DataSource::find($id);

        return response()->json(array(
            'error'      => false,
            'datasource' => $datasource->toArray()),
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $datasource = DataSource::find($id);
        $datasource->title = $request->input('title', $datasource->title);
        $datasource->description = $request->input('description', $datasource->description);
        $datasource->url = $request->input('url', $datasource->url);

        $datasource->config = $request->input('config', $datasource->config);
        $datasource->config_status = $request->input('config_status', $datasource->config_status);

        $datasource->save();

        return response()->json(array(
            'error'      => false,
            'datasource' => $datasource->toArray()),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        DataSource::find($id)->delete();

        return response()->json(array(
            'error'   => false,
            'message' => 'Datasource deleted.'),
            200
        );
    }

}
