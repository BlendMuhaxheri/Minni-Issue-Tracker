<?php

namespace App\Http\Controllers\Issues;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\AttachTagRequest;
use App\Models\Issue;
use App\Models\Tag;

class IssueTagController extends Controller
{
    public function attach(AttachTagRequest $request, Issue $issue)
    {
        $this->authorize('update', $issue);

        $issue->tags()->syncWithoutDetaching([
            $request->integer('tag_id'),
        ]);

        return response()->json([
            'attached' => true,
            'tag_id' => $request->integer('tag_id'),
        ]);
    }

    public function detach(Issue $issue, Tag $tag)
    {
        $this->authorize('update', $issue);

        $issue->tags()->detach($tag->id);

        return response()->json([
            'detached' => true,
            'tag_id' => $tag->id,
        ]);
    }
}
