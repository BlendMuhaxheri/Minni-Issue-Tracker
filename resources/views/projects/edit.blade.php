<x-app-layout>
    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-2xl font-bold">Edit Project</h1>

        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('projects.update', $project) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <input
                name="name"
                value="{{ old('name', $project->name) }}"
                placeholder="Project name"
                class="border p-2 w-full">

            <textarea
                name="description"
                placeholder="Description"
                class="border p-2 w-full">{{ old('description', $project->description) }}</textarea>

            <div class="grid gap-4 sm:grid-cols-2">
                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}"
                    class="border p-2 w-full">

                <input
                    type="date"
                    name="deadline"
                    value="{{ old('deadline', optional($project->deadline)->format('Y-m-d')) }}"
                    class="border p-2 w-full">
            </div>

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Update Project
            </button>
        </form>
    </div>
</x-app-layout>
