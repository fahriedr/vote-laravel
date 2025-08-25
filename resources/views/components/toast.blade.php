@props(['type' => 'success'])

<div 
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 4000)"
    class="fixed top-5 right-5 z-50"
>
    <div 
        @class([
            'px-4 py-3 rounded shadow-lg text-white',
            'bg-green-500' => $type === 'success',
            'bg-red-500' => $type === 'error',
        ])
    >
        {{ $slot }}
    </div>
</div>
