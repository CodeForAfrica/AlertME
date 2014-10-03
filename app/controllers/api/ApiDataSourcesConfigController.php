<?php

class ApiDataSourceConfigController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$configs =  DataSourceConfig::all();
		return Response::json(array(
				'error' => false,
				'configs' => $configs->toArray()),
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
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$config =  DataSource::find($id)->datasourceconfig;
		return Response::json(array(
				'error' => false,
				'config' => $config->toArray()),
				200
		);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
		$config = DataSourceConfig::find($id);
		$config->data_source_columns = Input::get('data_source_columns');
		$config->config_status = Input::get('config_status');
		$config->config = json_encode(Input::get('config'));

		$config->save();
		return Response::json(array(
				'error' => false,
				'config' => $config->toArray()),
				200
		);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
