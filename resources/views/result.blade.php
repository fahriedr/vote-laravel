<x-guest-layout>
    <div class="py-12 px-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 rounded-xl p-8 mb-6 shadow-2xl">
                <div class="mb-8">
                    <h1 class="text-white text-3xl font-bold mb-2">
                        {{ $data->question ?? 'Best Band in the world' }}
                    </h1>
                    <p class="text-gray-400 text-sm">
                        {{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left side - Results bars -->
                    <div class="space-y-6">
                        @php
                            $colors = ['bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-orange-500', 'bg-cyan-500', 'bg-lime-500', 'bg-amber-500'];
                            $chartColors = ['#EF4444', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#F97316', '#06B6D4', '#84CC16', '#FBBF24'];
                            $totalVotes = $data->votes_count;
                        @endphp
                        
                        @foreach($data->options as $index => $option)
                            @php
                                $percentage = $totalVotes > 0 ? round(($option->votes_count / $totalVotes) * 100, 1) : 0;
                                $colorClass = $colors[$index % count($colors)];
                            @endphp
                            
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-white text-lg font-medium">
                                        {{ $option->option_text }}
                                    </span>
                                    <span class="text-white text-lg font-bold">
                                        {{ $percentage }}% ({{ $option->votes_count }} votes)
                                    </span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                                    <div class="progress-bar {{ $colorClass }} h-3 rounded-full" 
                                        data-width="{{ $percentage }}" 
                                        style="width: 0%"></div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="pt-4 border-t border-gray-600">
                            <p class="text-gray-200 text-lg">Total Votes: {{ $totalVotes }}</p>
                        </div>
                    </div>
                    
                    <!-- Right side - Pie Chart -->
                    <div class="flex items-center justify-center">
                        <div class="w-80 h-80 relative">
                            <canvas id="pollChart" class="max-w-full max-h-full"></canvas>
                        </div>
                    </div>
                </div>
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
        const pollData = {
            labels: @json($data->options->pluck('option_text')),
            data: @json($data->options->pluck('votes_count')),
            colors: @json(
                $data->options->map(
                    fn($opt, $i) => $chartColors[$i % count($chartColors)]
                )
            ),
        };

        // Animate progress bars
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.querySelectorAll('.progress-bar').forEach(bar => {
                    const width = bar.getAttribute('data-width');
                    bar.style.width = width + '%';
                });
            }, 500);
        });

        // Create pie chart
        const ctx = document.getElementById('pollChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: pollData.labels,
                datasets: [{
                    data: pollData.data,
                    backgroundColor: pollData.colors,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#fff',
                            font: { size: 14 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} votes (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1500
                }
            }
        });

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
