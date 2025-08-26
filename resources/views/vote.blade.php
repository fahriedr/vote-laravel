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
        </div>
    </div>
    
</x-guest-layout>