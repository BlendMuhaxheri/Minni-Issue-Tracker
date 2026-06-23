<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">

        <h1 class="text-2xl font-bold">Projects</h1>

        <a href="{{ route('projects.create') }}" class="text-blue-600">
            + Create Project
        </a>

        <div class="mt-6 space-y-3">
            @foreach ($projects as $project)
            <div class="border p-4 rounded">

                <a href="{{ route('projects.show', $project) }}" class="font-bold text-lg">
                    {{ $project->name }}
                </a>

                <div class="text-sm text-gray-600">
                    {{ $project->description }}
                </div>

                <div class="text-xs mt-2 text-gray-500">
                    Issues: {{ $project->issues_count }}
                </div>

                @can('update', $project)
                    <div class="mt-2 flex gap-3 text-sm">
                        <a href="{{ route('projects.edit', $project) }}" class="text-blue-500">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('projects.destroy', $project) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500">
                                Delete
                            </button>
                        </form>
                    </div>
                @endcan

            </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
