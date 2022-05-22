<?php

namespace Phrshte\FullCms\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Phrshte\FullCms\Http\Requests\ContentRequest;
use Phrshte\FullCms\Http\Resources\Admin\ContentCollection;
use Phrshte\FullCms\Http\Resources\Admin\ContentResource;
use Phrshte\FullCms\Http\Resources\ExceptionResource;
use Phrshte\FullCms\Http\Resources\NullResource;
use Phrshte\FullCms\Models\Content;
use Phrshte\FullCms\Models\Tag;
use Exception;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $contents = Content::query();

        if (\request()->filled('category_slug')) {
            $slug = \request()->input('category_slug');
            if (is_array($slug)) {
                $contents->whereHas('category', function ($query) use ($slug) {
                    $query->whereIn('slug', $slug);
                });
            } else {
                $contents->whereHas('category', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                });
            }
        }

        if (\request()->filled('slug')) {
            $contents->where('slug', \request()->input('slug'));
        }

        if (\request()->filled('category_id')) {
            $contents->where('category_id', request()->input('category_id'));
        }

        if (\request()->filled('dropdown')) {
            $data = $contents->get(['id', 'title']);
        } else {
            $data = $contents->with('category')->latest()
                ->paginate(\request()->input('per_page') ?? 15);
        }
        
        return (new ContentCollection($data))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ContentRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ContentRequest $request)
    {
        $contentAttributes         = $request->safe(['category_id', 'title', 'slug', 'description', 'intro', 'cover_id']);
        $relatedContentsAttributes = $request->safe(['related_contents']);
        $extraValuesAttributes     = $request->safe(['fields']);
        $tagAttributes             = $request->safe(['tags']);

        DB::beginTransaction();
        try {
            $content = Content::create($contentAttributes);

            foreach ($extraValuesAttributes['fields'] as $attribute) {
                $content->extraValues()->create(
                    [
                        'extra_field_id' => $attribute['field_id'],
                        'value'          => $attribute['value'],
                    ]
                );
            }

            if ($tagAttributes['tags']) {
                foreach ($tagAttributes['tags'] as $attribute) {
                    $tag = Tag::firstOrCreate(
                        [
                            'title' => $attribute,
                        ]
                    );
                    $content->tags()->attach($tag);
                }
            }


            if (array_key_exists('related_contents', $relatedContentsAttributes)
                and count($relatedContentsAttributes['related_contents'])
            ) {
                $content->relatedContents()->attach($relatedContentsAttributes['related_contents']);
            }


            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return (new ExceptionResource($exception))->response()->setStatusCode(400);
        }

        return (new ContentResource($content))->response()->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \Phrshte\FullCms\Models\Content  $content
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Content $content)
    {
        $data = $content->load([
            'category',
            'tags',
            'cover',
            'relatedContents',
        ]);

        return (new ContentResource($data))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContentRequest  $request
     * @param  \Phrshte\FullCms\Models\Content  $content
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ContentRequest $request, Content $content)
    {
        $contentAttributes         = $request->safe(['category_id', 'title', 'slug', 'description', 'intro', 'cover_id']);
        $relatedContentsAttributes = $request->safe(['related_contents']);
        $extraValuesAttributes     = $request->safe(['fields']);
        $tagAttributes             = $request->safe(['tags']);

        DB::beginTransaction();
        try {
            $content->update($contentAttributes);

            $content->extraValues()->delete();
            foreach ($extraValuesAttributes['fields'] as $attribute) {
                $content->extraValues()->create(
                    [
                        'extra_field_id' => $attribute['field_id'],
                        'value'          => $attribute['value'],
                    ]
                );
            }

            $content->tags()->delete();
            foreach ($tagAttributes['tags'] as $attribute) {
                $tag = Tag::firstOrCreate(
                    [
                        'title' => $attribute,
                    ]
                );
                $content->tags()->attach($tag);
            }

            if (array_key_exists('related_contents', $relatedContentsAttributes)
                and count($relatedContentsAttributes['related_contents'])
            ) {
                $content->relatedContents()->attach($relatedContentsAttributes['related_contents']);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return (new ExceptionResource($exception))->response()->setStatusCode(400);
        }

        return (new ContentResource($content))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Phrshte\FullCms\Models\Content  $content
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Content $content)
    {
        try {
            $content->delete();

        } catch (Exception $exception) {

            return (new ExceptionResource($exception))->response()->setStatusCode(400);
        }

        return (new NullResource(null))->response()->setStatusCode(202);
    }
}
