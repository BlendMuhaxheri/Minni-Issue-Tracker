@foreach ($issues as $issue)
<a href="{{ route('issues.show', $issue) }}"
    class="block border p-3 rounded hover:bg-gray-50">

    <div class="font-bold">
        {{ $issue->title }}
    </div>

    <div class="text-sm text-gray-500">
        {{ ucfirst(str_replace('_', ' ', $issue->status->value ?? $issue->status)) }}
        •
        {{ ucfirst($issue->priority->value ?? $issue->priority) }}
    </div>

    <div class="text-xs mt-1">
        Project: {{ $issue->project->name ?? '-' }}
    </div>

    <div class="mt-2 flex gap-1 flex-wrap">
        @foreach ($issue->tags as $tag)
        <span class="text-xs bg-gray-200 px-2 py-1 rounded">
            {{ $tag->name }}
        </span>
        @endforeach
    </div>

</a>
@endforeach