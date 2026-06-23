<x-app-layout>
    <div class="max-w-2xl mx-auto py-8">

        <h1 class="text-2xl font-bold">Create Issue</h1>

        {{-- ERRORS --}}
        @if ($errors->any())
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('issues.store') }}" class="mt-6 space-y-4">
            @csrf

            {{-- PROJECT --}}
            <select name="project_id" class="border p-2 w-full">
                @foreach($projects as $project)
                <option value="{{ $project->id }}"
                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
                @endforeach
            </select>

            {{-- TITLE --}}
            <input
                name="title"
                value="{{ old('title') }}"
                placeholder="Title"
                class="border p-2 w-full">

            {{-- DESCRIPTION --}}
            <textarea
                name="description"
                class="border p-2 w-full">{{ old('description') }}</textarea>

            {{-- STATUS (ENUM SAFE) --}}
            <select name="status" class="border p-2 w-full">
                @foreach (\App\Enums\Issue\IssueStatus::cases() as $status)
                <option value="{{ $status->value }}"
                    {{ old('status', 'open') === $status->value ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                </option>
                @endforeach
            </select>

            {{-- PRIORITY (ENUM SAFE) --}}
            <select name="priority" class="border p-2 w-full">
                @foreach (\App\Enums\Issue\IssuePriority::cases() as $priority)
                <option value="{{ $priority->value }}"
                    {{ old('priority', 'low') === $priority->value ? 'selected' : '' }}>
                    {{ ucfirst($priority->value) }}
                </option>
                @endforeach
            </select>

            {{-- DUE DATE --}}
            <input
                type="date"
                name="due_date"
                value="{{ old('due_date') }}"
                class="border p-2 w-full">

            {{-- TAGS --}}
            <div>
                <label class="font-semibold">Tags</label>

                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach($tags as $tag)
                    <label>
                        <input
                            type="checkbox"
                            name="tags[]"
                            value="{{ $tag->id }}"
                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        {{ $tag->name }}
                    </label>
                    @endforeach
                </div>
            </div>

            <button class="bg-blue-500 text-white px-4 py-2">
                Create Issue
            </button>

        </form>

    </div>
</x-app-layout>
