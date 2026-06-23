<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">

        <h1 class="text-2xl font-bold">{{ $project->name }}</h1>

        <p class="text-gray-600 mt-2">
            {{ $project->description }}
        </p>

        <div class="mt-6">
            <h2 class="font-semibold">Issues</h2>

            <div class="mt-4 space-y-3">
                @forelse ($project->issues as $issue)
                <a href="{{ route('issues.show', $issue) }}"
                    class="block border p-3 rounded">

                    <div class="font-bold">
                        {{ $issue->title }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ ucfirst(str_replace('_', ' ', $issue->status->value)) }} • {{ ucfirst($issue->priority->value) }}
                    </div>

                    <div class="text-xs mt-1">
                        Tags:
                        @foreach($issue->tags as $tag)
                        <span class="px-2 py-1 bg-gray-200 rounded text-xs">
                            {{ $tag->name }}
                        </span>
                        @endforeach
                    </div>

                </a>
                @empty
                <p class="text-gray-500">No issues yet.</p>
                @endforelse
            </div>

        </div>

    </div>
</x-app-layout>
