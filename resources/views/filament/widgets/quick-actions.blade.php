<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <div class="flex flex-wrap gap-3">
            @forelse ($this->getActions() as $action)
                <x-filament::button tag="a" :href="$action['url']">
                    {{ $action['label'] }}
                </x-filament::button>
            @empty
                <p class="text-sm text-gray-500">No quick actions are available for your current permissions.</p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
