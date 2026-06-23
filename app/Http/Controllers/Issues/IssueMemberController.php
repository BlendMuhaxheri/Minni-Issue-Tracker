<?php

namespace App\Http\Controllers\Issues;

use App\Http\Controllers\Controller;
use App\Http\Requests\Issues\AttachIssueMemberRequest;
use App\Models\Issue;
use App\Models\User;

class IssueMemberController extends Controller
{
    /**
     * Attach a user to an issue (AJAX)
     */
    public function attach(AttachIssueMemberRequest $request, Issue $issue)
    {
        $this->authorize('update', $issue);

        $issue->members()->syncWithoutDetaching([
            $request->integer('user_id'),
        ]);

        return response()->json([
            'attached' => true,
            'user_id' => $request->integer('user_id'),
        ]);
    }

    /**
     * Detach a user from an issue (AJAX)
     */
    public function detach(Issue $issue, User $user)
    {
        $this->authorize('update', $issue);

        $issue->members()->detach($user->id);

        return response()->json([
            'detached' => true,
            'user_id' => $user->id,
        ]);
    }
}
