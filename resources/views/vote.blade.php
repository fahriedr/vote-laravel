<x-guest-layout>
    <div class="py-12 px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-xl p-8 mb-6 shadow-2xl">
                <form action="{{route('vote.create', ['pollId' => $data->id]) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <h1 class="text-white text-2xl font-bold mb-2">{{$data->question}}</h1>
                        <p class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}</p>
                    </div>

                    <input type="hidden" name="poll_id" value="{{$data->id}}">
                    <input type="hidden" name="fingerprint">

                    <div class="mb-8">
                        <p class="text-white text-lg mb-6">Make a vote:</p>
                        
                        <!-- Voting Options -->
                        <div class="space-y-4 mb-8">

                            @foreach ($data->options as $item)
                                <label class="block">
                                    <input type="radio" name="option_id" value="{{$item->id}}" class="sr-only peer">
                                    <div class="bg-gray-700 border border-gray-600 rounded-lg p-4 text-center text-white cursor-pointer hover:bg-gray-650 peer-checked:bg-blue-600 peer-checked:border-blue-500 transition-all duration-200">
                                        {{$item->option_text}}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @if ($data->require_voter_name)
                            <label class="tracking-wider text-lg mb-1 block font-bold text-white">
                                Name
                            </label>
                            <input type="text" name="name" class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded-md shadow-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-6" placeholder="Enter your name" required>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <button id="voteBtn" type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
                                Vote <i class="fas fa-arrow-right"></i>
                            </button>
                            
                            <a id="showResultBtn" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2 border border-gray-600 cursor-pointer" href="{{route('vote.result', ['pollId' => $data->unique_id])}}">
                                <i class="fas fa-chart-bar"></i> Show Result
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-gray-800 rounded-xl p-6 shadow-2xl">
                <button class="flex items-center gap-2 text-gray-300 mb-4">
                    <i class="fas fa-share"></i> Share
                </button>
                
                <div class="mb-4">
                    <p class="text-white text-lg mb-3">Share the link</p>
                    <div class="flex">
                        <input type="text" value="{{url()->current()}}" 
                            class="flex-1 bg-gray-700 border border-gray-600 rounded-l-lg px-4 py-2 text-gray-300 text-sm" readonly>
                        <button id="copyBtn" class="bg-gray-700 border border-gray-600 border-l-0 rounded-r-lg px-4 py-2 text-gray-300 hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        document.getElementById('copyBtn').addEventListener('click', function() {
            const linkInput = document.querySelector('input[readonly]');
            linkInput.select();
            document.execCommand('copy');
            
            // Show feedback
            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => {
                this.innerHTML = originalIcon;
            }, 2000);
        });
    </script>
</x-guest-layout>