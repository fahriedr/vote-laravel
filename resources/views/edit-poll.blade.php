<x-app-layout>
    <div class="py-12 px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg rounded-lg">
                <div class="py-6 px-8 text-gray-900 dark:text-gray-100">
                    
                    <form method="POST" action="{{ route('polls.update', $data->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="tracking-wider text-lg mb-1 block font-bold">
                                Title
                            </label>
                            <input type="text" name="question" value="{{ old('question', $data->question) }}"
                                   class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter poll title" required>
                        </div>

                        <!-- Options -->
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">Options:</label>

                            <div id="options-wrapper" class="space-y-2">
                                @foreach ($data->options as $option)
                                    <div class="flex items-center mb-2">
                                        <!-- hidden id -->
                                        <input type="hidden" name="options[{{ $loop->index }}][id]" value="{{ $option->id }}">
                                        <!-- text -->
                                        <input type="text" 
                                            name="options[{{ $loop->index }}][option_text]" 
                                            value="{{ $option->option_text }}"
                                            class="flex-1 border rounded p-2 bg-gray-800 text-gray-200">
                                            
                                        <button type="button" onclick="removeOption(this)" class="ml-2 text-red-500 font-bold">×</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" onclick="addOption()" id="add-option-btn" class="mt-3 bg-blue-500 text-white px-3 py-1 rounded">
                                + Add option
                            </button>
                        </div>

                        <hr class="my-6">

                        <!-- Settings -->
                        <h2 class="text-xl font-semibold mb-4">Settings</h2>
                        <div class="grid grid-cols-2 gap-6">

                            <div class="space-y-3">
                                <label class="flex items-center justify-between">
                                    <span>Allow select multiple options</span>
                                    <input type="checkbox" name="allow_multiple" value="1" class="toggle-checkbox"
                                           {{ old('allow_multiple', $data->allow_multiple ?? false) ? 'checked' : '' }}>
                                </label>

                                <label class="flex items-center justify-between">
                                    <span>Require participant name</span>
                                    <input type="checkbox" name="require_voter_name" value="1" class="toggle-checkbox"
                                           {{ old('require_voter_name', $data->require_voter_name ?? false) ? 'checked' : '' }}>
                                </label>
                            </div>

                            <div class="space-y-3">
                                <label class="flex items-center justify-between">
                                    <span>Close poll with schedule date</span>
                                    <input type="checkbox" name="has_schedule" value="1" class="toggle-checkbox" onchange="toggleSchedule(this)"
                                           {{ old('end_date', $data->end_date) ? 'checked' : '' }}>
                                </label>

                                <div id="schedule-date-wrapper" class="{{ old('end_date', $data->end_date) ? '' : 'hidden' }}">
                                    <input type="datetime-local" name="end_date"
                                           value="{{ old('end_date', $data->end_date ? \Carbon\Carbon::parse($data->end_date)->format('Y-m-d\TH:i') : '') }}"
                                           class="mt-2 w-full border rounded p-2 bg-gray-800 text-gray-200">
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full my-4">
                            Update Poll
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let optionCount = {{ $data->options->count() }};
        const maxOptions = 5;
        const addBtn = document.getElementById('add-option-btn');

        function addOption() {
            if (optionCount >= maxOptions) return;

            optionCount++;
            const wrapper = document.getElementById('options-wrapper');

            const div = document.createElement('div');
            div.classList.add('flex', 'items-center');

            div.innerHTML = `
                <input type="text" name="options[]" placeholder="Option ${optionCount}"
                       class="flex-1 border rounded p-2 bg-gray-800 text-gray-200">
                <button type="button" onclick="removeOption(this)"
                        class="ml-2 text-red-500 font-bold">×</button>
            `;

            wrapper.appendChild(div);
            checkMaxOptions();
        }

        function removeOption(button) {
            if (optionCount == 1) return;
            button.parentElement.remove();
            optionCount--;
            checkMaxOptions();
        }

        function checkMaxOptions() {
            if (optionCount >= maxOptions) {
                addBtn.disabled = true;
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                addBtn.disabled = false;
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function toggleSchedule(checkbox) {
            const wrapper = document.getElementById('schedule-date-wrapper');
            wrapper.classList.toggle('hidden', !checkbox.checked);
        }
    </script>
</x-app-layout>
