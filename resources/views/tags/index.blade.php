<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold">Tags</h1>

        @if (session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tags.store') }}" class="mt-6 grid gap-3 sm:grid-cols-[1fr_160px_auto]">
            @csrf

            <input
                name="name"
                value="{{ old('name') }}"
                placeholder="Tag name"
                class="border p-2 w-full">

            <input
                name="color"
                value="{{ old('color') }}"
                placeholder="#4f46e5"
                class="border p-2 w-full">

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Create
            </button>
        </form>

        <div class="mt-6 space-y-2">
            @forelse ($tags as $tag)
                <div class="border p-3 rounded flex items-center gap-3">
                    <span
                        class="inline-block h-4 w-4 rounded"
                        style="background-color: {{ $tag->color ?: '#e5e7eb' }}"></span>

                    <span class="font-medium">{{ $tag->name }}</span>
                    <span class="text-sm text-gray-500">{{ $tag->color }}</span>
                </div>
            @empty
                <p class="text-gray-500">No tags yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
