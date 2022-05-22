<?php

namespace Phrshte\FullCms\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Phrshte\FullCms\Http\Resources\CommentResource;
use Phrshte\FullCms\Models\Comment;
use Illuminate\Http\Request;

class UpdateCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Phrshte\FullCms\Models\Comment  $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Phrshte\FullCms\Models\Comment  $comment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $attributes = $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update($attributes);
        $comment->refresh();

        return (new CommentResource($comment->refresh()))->response()
            ->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Phrshte\FullCms\Models\Comment  $comment
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
