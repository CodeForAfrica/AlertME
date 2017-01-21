<?php namespace Greenalert\Http\Controllers\api;

use Greenalert\Category;
use Greenalert\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiCategoryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        if ($request->input('pivot') == 1) {
            foreach ($categories as $key => $category) {
                $pivot = \DB::table('project_category')
                    ->where('category_id', $category->id)
                    ->lists('project_id');
                $categories[ $key ] = array_add($categories[ $key ], 'projects_pivot', $pivot);
            }
        }

        return response()->json(array(
            'error'      => false,
            'categories' => $categories->toArray()
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
    public function store(Request $request)
    {
        $category = new Category;
        $category->title = $request->input('title');
        $category->description = $request->input('desc');
        $category->keywords = $request->input('keywords');
        $category->icon_url = $request->input('icon_url');

        $category->save();

        return response()->json(array(
            'error'    => false,
            'category' => $category->toArray()
        ),
            201
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
        $category = Category::find($id);
        $projects = \DB::table('project_category')
            ->where('category_id', $id)
            ->select('id', 'project_id', 'category_id')
            ->get();

        return response()->json(array(
            'error'    => false,
            'category' => $category->toArray(),
            'projects' => $projects
        ),
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
    public function update($id)
    {
        $category = Category::find($id);
        $category->title = \Input::get('title');
        $category->description = \Input::get('desc');
        $category->keywords = \Input::get('keywords');
        $category->icon_url = \Input::get('icon_url');

        $category->save();

        return response()->json(array(
            'error'    => false,
            'category' => $category->toArray()),
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
        Category::find($id)->delete();

        return response()->json(array(
            'error'   => false,
            'message' => 'Category deleted.'),
            200
        );
    }

}
