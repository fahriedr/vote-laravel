<x-guest-layout>
    <div class="max-w-4xl mx-auto py-8 px-4">

        <a type="button" href="{{route('polls.create')}}" class="mt-3 mb-10 bg-blue-500 text-white px-3 py-3 rounded">
            Make Poll Now!
        </a>
        <!-- Search Bar -->
        <div x-data="{ loading: false }" class="mt-4">
            <form @submit="loading = true" method="GET" action="{{ route('polls.search') }}" class="mb-6 flex">
                <input type="text" name="q" placeholder="Search here..." 
                    value="{{ request('q') }}"
                    class="w-full border rounded-l-lg p-2 bg-gray-800 text-gray-200" />
                <button type="submit" class="bg-blue-500 text-white px-4 rounded-r-lg">Search</button>
            </form>
            <div x-show="loading" class="text-gray-400">Loading..</div>
        </div>

        <!-- Results -->
        @if($polls->count())
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($polls as $poll)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 flex justify-between items-center">
                        <div>
                            <h2 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                {{ $poll->question }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $poll->votes_count }} votes • Created at {{ $poll->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <a href="{{ route('vote.show', $poll->unique_id) }}" 
                           target="_blank" 
                           class="text-blue-500 hover:underline">
                            View
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $polls->links() }}
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No polls found.</p>
        @endif
    </div>
</x-guest-layout>
