<?php

class ApiCategoryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$categories =  Category::all();
		$projects = DB::table('project_category')
									->select('id', 'project_id', 'category_id')
									->get();
		return Response::json(array(
				'error' => false,
				'categories' => $categories->toArray(),
				'projects' => $projects
			),
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
		$category = new Category;
		$category->title = Input::get('title');
		$category->description = Input::get('desc');
		$category->keywords = Input::get('keywords');
		$category->icon_url = Input::get('icon_url');

		$category->save();

		return Response::json(array(
				'error' => false,
				'category' => $category->toArray()
			),
			200
		);
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
		$category =  Category::find($id);
		$projects = DB::table('project_category')
									->where('category_id', $id)
									->select('id', 'project_id', 'category_id')
									->get();
		return Response::json(array(
				'error' => false,
				'category' => $category->toArray(),
				'projects' => $projects
			),
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
		$category = Category::find($id);
		$category->title = Input::get('title');
		$category->description = Input::get('desc');
		$category->keywords = Input::get('keywords');
		$category->icon_url = Input::get('icon_url');

		$category->save();

		return Response::json(array(
				'error' => false,
				'category' => $category->toArray()),
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
		Category::find($id)->delete();
		return Response::json(array(
				'error' => false,
				'message' => 'Category deleted.'),
				200
		);
	}


}
