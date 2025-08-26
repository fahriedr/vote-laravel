<x-app-layout>

    <div class="py-12 px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg rounded-lg">
                <div class="py-6 px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{route('polls.store')}}">
                        @csrf
                        <div class="mb-4">
                            <label class="tracking-wider text-lg mb-1 block font-bold">
                                Title
                            </label>
                            <input type="text" name="question" class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter poll title" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold mb-2">Options:</label>

                            <div id="options-wrapper" class="space-y-2">
                                <div class="flex items-center">
                                    <input type="text" name="options[]" placeholder="Option 1" class="flex-1 border rounded p-2 bg-gray-800 text-gray-200">
                                    <button type="button" onclick="removeOption(this)" class="ml-2 text-red-500 font-bold">×</button>
                                </div>
                            </div>

                            <button type="button" onclick="addOption()" id="add-option-btn" class="mt-3 bg-blue-500 text-white px-3 py-1 rounded">
                                + Add option
                            </button>
                        </div>

                        <hr class="my-6">

                        <h2 class="text-xl font-semibold mb-4">Settings</h2>
                        <div class="grid grid-cols-2 gap-6">

                            <div class="space-y-3">
                                {{-- <label class="flex items-center justify-between">
                                    <span>Allow select multiple options</span>
                                    <input type="checkbox" name="allow_multiple" value="1" class="toggle-checkbox">
                                </label> --}}

                                <label class="flex items-center justify-between">
                                    <span>Require participant name</span>
                                    <input type="checkbox" name="require_voter_name" value="1" class="toggle-checkbox">
                                </label>
                            </div>

                            <div class="space-y-3">


                                <label class="flex items-center justify-between">
                                    <span>Close poll with schedule date</span>
                                    <input type="checkbox" name="has_schedule" value="1" class="toggle-checkbox" onchange="toggleSchedule(this)">
                                </label>

                                <div id="schedule-date-wrapper" class="hidden">
                                    <input type="datetime-local" name="end_date" class="mt-2 w-full border rounded p-2 bg-gray-800 text-gray-200">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full my-4">
                            Save Poll
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let optionCount = 1;
        const maxOptions = 5;
        const addBtn = document.getElementById('add-option-btn');

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

        function similarityCheck(input1, input2, sensitive = false) {
            if (!sensitive) {
                input1 = input1.toLowerCase();
                input2 = input2.toLowerCase();
            }

            let matchCount = 0;
            for (let char of input1) {
                if (input2.includes(char)) {
                    matchCount++;
                }
            }

            return (matchCount / input1.length) * 100;
        }

        const optionsWrapper = document.getElementById('options-wrapper');

        function checkDuplicateOptions(newInput) {
            const inputs = optionsWrapper.querySelectorAll("input[name='options[]']");
            let threshold = 70; // minimal similarity dianggap mirip

            inputs.forEach(input => {
                if (input !== newInput && input.value.trim() !== "") {
                    let percent = similarityCheck(newInput.value, input.value, false); // non-sensitive
                    if (percent >= threshold) {
                        showToast(`"${newInput.value}" mirip dengan "${input.value}" (${percent.toFixed(0)}%)`, 'error');
                    }
                }
            });
        }

        function attachOptionListener(input) {
            input.addEventListener('blur', function() {
                checkDuplicateOptions(this);
            });
        }

        // initial
        optionsWrapper.querySelectorAll("input[name='options[]']").forEach(attachOptionListener);

        function addOption() {
            if (optionCount >= maxOptions) return;

            optionCount++;
            const wrapper = document.getElementById('options-wrapper');

            const div = document.createElement('div');
            div.classList.add('flex', 'items-center');

            const input = document.createElement('input');
            input.type = "text";
            input.name = "options[]";
            input.placeholder = `Option ${optionCount}`;
            input.className = "flex-1 border rounded p-2 bg-gray-800 text-gray-200";

            attachOptionListener(input);

            const button = document.createElement('button');
            button.type = "button";
            button.innerText = "×";
            button.className = "ml-2 text-red-500 font-bold";
            button.onclick = function() { removeOption(button) };

            div.appendChild(input);
            div.appendChild(button);
            wrapper.appendChild(div);

            checkMaxOptions();
        }

        function showToast(message, type = 'error') {
            const container = document.getElementById('dynamic-toast-container');

            const toast = document.createElement('div');
            toast.className = `fixed top-5 right-5 z-50 px-4 py-3 rounded shadow-lg text-white transition-opacity duration-500 opacity-100
                ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;

            toast.innerText = message;
            container.appendChild(toast);

            // Auto hide setelah 4 detik
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }
    </script>
</x-app-layout>