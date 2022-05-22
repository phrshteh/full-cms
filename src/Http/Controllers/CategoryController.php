<?php

namespace Phrshte\FullCms\Http\Controllers;

use Illuminate\Routing\Controller;
use Phrshte\FullCms\Http\Resources\CategoryCollection;
use Phrshte\FullCms\Http\Resources\CategoryResource;
use Phrshte\FullCms\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::query()->where('parent_id', null)->with('children');

        if (\request()->input('id')) {
            $categories->where('id', \request()->input('id'));
        }

        $data = $categories->get();

        return (new CategoryCollection($data))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Phrshte\FullCms\Models\Category  $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        $category = $category->load(['contents.extraValues.extraField', 'contents.cover', 'children']);

        return (new CategoryResource($category))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Phrshte\FullCms\Models\Category  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Phrshte\FullCms\Models\Category  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
