<x-filament-pages::page>
    <form wire:submit.prevent="submit" class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
        {{ $this->form }}

        <div class="mt-6">
            <button type="submit" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:ring-offset-primary-600">
                Submit
            </button>
        </div>
    </form>
</x-filament-pages::page>