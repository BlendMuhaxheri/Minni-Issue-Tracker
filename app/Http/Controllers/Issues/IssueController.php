<?php

namespace App\Http\Controllers\Issues;

use App\Enums\Issue\IssuePriority;
use App\Enums\Issue\IssueStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Issues\StoreIssueRequest;
use App\Http\Requests\Issues\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $issues = Issue::query()
            ->with(['project', 'tags'])
            ->filter($request->only(['status', 'priority', 'tag', 'search']))
            ->latest()
            ->get();

        if ($request->ajax()) {
            return view('issues.partials.list', compact('issues'))->render();
        }

        return view('issues.index', [
            'issues' => $issues,
            'statuses' => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
            'tags' => Tag::all(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Issue::class);

        return view('issues.create', [
            'projects' => Project::all(),
            'tags' => Tag::all(),
            'statuses' => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
        ]);
    }

    public function store(StoreIssueRequest $request)
    {
        $data = $request->validated();
        $project = Project::findOrFail($data['project_id']);

        $this->authorize('update', $project);

        $issue = Issue::create(Arr::except($data, ['tags']));

        if (! empty($data['tags'])) {
            $issue->tags()->sync($data['tags']);
        }

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue created successfully');
    }

    public function show(Issue $issue)
    {
        $this->authorize('view', $issue);

        $issue->load([
            'project',
            'tags',
            'members',
        ]);

        return view('issues.show', compact('issue'));
    }

    public function edit(Issue $issue)
    {
        $this->authorize('update', $issue);

        return view('issues.edit', [
            'issue' => $issue,
            'projects' => Project::all(),
            'tags' => Tag::all(),
            'statuses' => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
        ]);
    }

    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $this->authorize('update', $issue);

        $data = $request->validated();

        $issue->update(Arr::except($data, ['tags']));

        if (isset($data['tags'])) {
            $issue->tags()->sync($data['tags']);
        } else {
            $issue->tags()->detach();
        }

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully');
    }

    public function destroy(Issue $issue)
    {
        $this->authorize('delete', $issue);

        $issue->delete();

        return redirect()
            ->route('issues.index')
            ->with('success', 'Issue deleted successfully');
    }
}
