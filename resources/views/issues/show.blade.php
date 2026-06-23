<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <h1 class="text-2xl font-bold">{{ $issue->title }}</h1>

        <p class="text-gray-600 mt-2">
            {{ $issue->description }}
        </p>

        <div class="mt-4">
            <span>Status: {{ ucfirst(str_replace('_', ' ', $issue->status->value)) }}</span>
            <span class="ml-4">Priority: {{ ucfirst($issue->priority->value) }}</span>
        </div>

        {{-- ========================= --}}
        {{-- TAGS --}}
        {{-- ========================= --}}
        <div class="mt-6">
            <h2 class="font-semibold">Tags</h2>

            <div id="tag-list" class="flex gap-2 mt-2">
                @foreach ($issue->tags as $tag)
                <span
                    class="px-2 py-1 bg-gray-200 rounded"
                    data-tag-id="{{ $tag->id }}">
                    {{ $tag->name }}
                </span>
                @endforeach
            </div>

            <button id="open-tag-modal" class="mt-2 text-blue-600">
                Manage Tags
            </button>
        </div>

        {{-- ========================= --}}
        {{-- MEMBERS (BONUS) --}}
        {{-- ========================= --}}
        <div class="mt-6">
            <h2 class="font-semibold">Members</h2>

            <div id="member-list" class="flex gap-2 mt-2 flex-wrap">
                @foreach ($issue->members as $member)
                <span
                    class="px-2 py-1 bg-blue-100 rounded"
                    data-member-id="{{ $member->id }}">
                    {{ $member->name }}
                </span>
                @endforeach
            </div>

            <button id="open-member-modal" class="mt-2 text-blue-600">
                Manage Members
            </button>
        </div>

        {{-- ========================= --}}
        {{-- COMMENTS --}}
        {{-- ========================= --}}
        <div class="mt-10">
            <h2 class="font-semibold">Comments</h2>

            <form id="comment-form" class="mt-2">
                <input
                    type="text"
                    name="author_name"
                    placeholder="Your name"
                    class="border p-2 w-full mb-2">

                <textarea
                    name="body"
                    placeholder="Comment..."
                    class="border p-2 w-full"></textarea>

                <button class="mt-2 bg-blue-500 text-white px-4 py-2">
                    Add Comment
                </button>

                <div id="comment-errors" class="text-red-500 mt-2"></div>
            </form>

            <div id="comment-list" class="mt-4 space-y-3"></div>
        </div>

    </div>

    {{-- ========================= --}}
    {{-- TAG MODAL --}}
    {{-- ========================= --}}
    <div id="tag-modal" class="hidden fixed inset-0 bg-black bg-opacity-50">
        <div class="bg-white p-4 max-w-md mx-auto mt-20 rounded">

            <h2 class="font-bold text-lg mb-3">Manage Tags</h2>

            <form id="tag-form" class="mb-4 space-y-2">
                <input
                    type="text"
                    name="name"
                    placeholder="Tag name"
                    class="border p-2 w-full">

                <input
                    type="text"
                    name="color"
                    placeholder="#4f46e5"
                    class="border p-2 w-full">

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    Create Tag
                </button>

                <div id="tag-errors" class="text-red-500 text-sm"></div>
            </form>

            <div id="tag-modal-list" class="space-y-2"></div>

            <button id="close-tag-modal" class="mt-4 text-red-500">
                Close
            </button>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- MEMBER MODAL --}}
    {{-- ========================= --}}
    <div id="member-modal" class="hidden fixed inset-0 bg-black bg-opacity-50">
        <div class="bg-white p-4 max-w-md mx-auto mt-20 rounded">

            <h2 class="font-bold text-lg mb-3">Manage Members</h2>

            <div id="member-modal-list" class="space-y-2"></div>
            <div id="member-errors" class="text-red-500 text-sm mt-2"></div>

            <button id="close-member-modal" class="mt-4 text-red-500">
                Close
            </button>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- DATA BRIDGE --}}
    {{-- ========================= --}}
    <div
        id="app-data"
        data-issue-id="{{ $issue->id }}"
        data-csrf="{{ csrf_token() }}"
        data-comments-url="{{ route('issues.comments.index', $issue) }}"
        data-store-comment-url="{{ route('issues.comments.store', $issue) }}"
        data-tags-url="{{ route('tags.index') }}"
        data-store-tag-url="{{ route('tags.store') }}"
        data-attach-tag-url="{{ route('issues.tags.attach', $issue) }}"
        data-detach-tag-url-template="{{ route('issues.tags.detach', [$issue, '__TAG__']) }}"
        data-users-url="{{ route('users.index') }}"
        data-attach-member-url="{{ route('issues.members.attach', $issue) }}"
        data-detach-member-url-template="{{ route('issues.members.detach', [$issue, '__USER__']) }}"
        data-attached-tags='@json($issue->tags->pluck("id")->values())'
        data-attached-members='@json($issue->members->pluck("id")->values())'>
    </div>
</x-app-layout>
