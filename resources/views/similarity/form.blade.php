<x-app-layout>
    <div class="py-12 px-8">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Similarity Checker</h2>

            <form method="POST" action="{{ route('similarity.check') }}">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold mb-1 text-white">Input 1</label>
                    <input type="text" name="input1" class="w-full border rounded p-2 bg-gray-800 text-gray-200" value="{{ old('input1') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1 text-white">Input 2</label>
                    <input type="text" name="input2" class="w-full border rounded p-2 bg-gray-800 text-gray-200" value="{{ old('input2') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1 text-white">Type</label>
                    <select name="type" class="w-full border rounded p-2 bg-gray-800 text-gray-200">
                        <option value="sensitive" {{ old('type')=='sensitive' ? 'selected' : '' }}>Sensitive Case</option>
                        <option value="non-sensitive" {{ old('type')=='non-sensitive' ? 'selected' : '' }}>Non-Sensitive Case</option>
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Check Similarity</button>
            </form>

            @if(session('result'))
                <div class="mt-6 p-4 bg-gray-100 dark:bg-gray-700 rounded">
                    <h3 class="font-bold text-lg mb-2 text-white">Result</h3>
                    <p class="text-white"><strong>Input 1:</strong> {{ session('result')['input1'] }}</p>
                    <p class="text-white"><strong>Input 2:</strong> {{ session('result')['input2'] }}</p>
                    <p class="text-white"><strong>Type:</strong> {{ ucfirst(session('result')['type']) }}</p>
                    <p class="text-white"><strong>Match:</strong> {{ session('result')['match'] }} / {{ session('result')['total'] }}</p>
                    <p class="font-bold text-green-600">Similarity: {{ session('result')['result'] }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
