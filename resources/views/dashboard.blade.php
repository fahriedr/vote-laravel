<x-app-layout>
    <div class="py-12 px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg rounded-lg">
                <div class="py-6 px-8 text-gray-900 dark:text-gray-100">
                    
                    <div class="overflow-x-auto">
                        <pre>{{ session('error') }}</pre>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase">#</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase">Question</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase">Total Votes</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase">Created At</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($data as $index => $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                    >
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('vote.create', $item->unique_id) }}" target="_blank">{{ $item->question }}</a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $item->votes_count }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <a href="{{ route('polls.edit', $item->id) }}" class="ml-3 text-yellow-500 hover:underline">Edit</a>
                                            <form method="POST" 
                                                action="{{ route('polls.delete', $item->id) }}" 
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this poll? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-3 text-red-500 hover:underline">Delete</button>
                                            </form>
                                            <a href="{{ route('vote.result', $item->unique_id) }}" target="_blank" class="ml-3 text-blue-500 hover:underline">Result</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
