<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Issue;

class CommentController extends Controller
{
    public function index(Issue $issue)
    {
        $this->authorize('view', $issue);

        $comments = $issue->comments()
            ->latest()
            ->paginate(10);

        return response()->json([
            'comments' => $comments,
        ]);
    }

    public function store(StoreCommentRequest $request, Issue $issue)
    {
        $this->authorize('view', $issue);

        $comment = $issue->comments()->create($request->validated());

        return response()->json([
            'comment' => $comment,
        ], 201);
    }
}
