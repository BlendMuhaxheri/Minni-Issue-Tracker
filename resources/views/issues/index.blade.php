<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">

        <h1 class="text-2xl font-bold">Issues</h1>

        {{-- FILTERS --}}
        <form id="filters" method="GET" class="mt-4 flex gap-4 flex-wrap">

            {{-- SEARCH --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search issues..."
                class="border p-2" />

            {{-- STATUS --}}
            <select name="status" class="border p-2">
                <option value="">All Status</option>
                @foreach ($statuses as $status)
                <option value="{{ $status->value }}"
                    {{ request('status') === $status->value ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                </option>
                @endforeach
            </select>

            {{-- PRIORITY --}}
            <select name="priority" class="border p-2">
                <option value="">All Priority</option>
                @foreach ($priorities as $priority)
                <option value="{{ $priority->value }}"
                    {{ request('priority') === $priority->value ? 'selected' : '' }}>
                    {{ ucfirst($priority->value) }}
                </option>
                @endforeach
            </select>

            {{-- TAG --}}
            <select name="tag" class="border p-2">
                <option value="">All Tags</option>
                @foreach ($tags as $tag)
                <option value="{{ $tag->id }}"
                    {{ request('tag') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
                @endforeach
            </select>

            <button class="bg-blue-500 text-white px-4 py-2">
                Filter
            </button>

        </form>

        {{-- ISSUES LIST --}}
        <div class="issues-list mt-6 space-y-3">

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

        </div>

    </div>
</x-app-layout>