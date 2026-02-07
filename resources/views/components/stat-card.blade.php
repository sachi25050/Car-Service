@props(['title', 'value', 'icon', 'trend' => null, 'color' => 'primary'])

<div class="stat-card">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
            @if($trend)
            <p class="text-xs mt-2 {{ $trend['positive'] ? 'text-green-600' : 'text-red-600' }}">
                <i class="bi bi-arrow-{{ $trend['positive'] ? 'up' : 'down' }}"></i>
                {{ $trend['value'] }}
            </p>
            @endif
        </div>
        <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
            <i class="bi bi-{{ $icon }} text-{{ $color }}-600 text-xl"></i>
        </div>
    </div>
</div>
