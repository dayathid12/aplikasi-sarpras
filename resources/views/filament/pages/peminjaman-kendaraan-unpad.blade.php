<div class="container mx-auto p-6">
    {{-- UNPAD Logo --}}
    <div class="flex justify-center mb-8">
        <div class="unpad-logo text-center">
            <div class="text-4xl font-bold text-blue-800">UNPAD</div>
            <div class="text-sm text-gray-600">Universitas Padjadjaran</div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-center mb-8 text-gray-800 dark:text-gray-200">{{ static::$title }}</h2>

    {{-- Stepper UI --}}
    <div class="flex justify-center mb-8">
        <ol class="flex items-center w-full max-w-lg text-sm font-medium text-center text-gray-500 sm:text-base dark:text-gray-400">
            <li class="flex md:w-full items-center text-blue-600 dark:text-blue-500 sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                    @if ($currentStep > 1)
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                        </svg>
                    @else
                        <span class="me-2">1</span>
                    @endif
                    Informasi <span class="hidden sm:inline-flex sm:ms-2">Peminjam</span>
                </span>
            </li>
            <li class="flex items-center @if($currentStep >= 2) text-blue-600 dark:text-blue-500 @endif">
                @if ($currentStep > 2)
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                @else
                    <span class="me-2">2</span>
                @endif
                Detail <span class="hidden sm:inline-flex sm:ms-2">Perjalanan</span>
            </li>
        </ol>
    </div>

    <form wire:submit.prevent="@if($currentStep == 2) finish @else nextStep @endif" class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
        {{-- Step 1: Personal Details --}}
        @if ($currentStep == 1)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{ $this->form }}
            </div>
        @endif

        {{-- Step 2: Trip Details --}}
        @if ($currentStep == 2)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{ $this->form }}
            </div>
        @endif

        {{-- Navigation Buttons --}}
        <div class="mt-8 flex justify-between">
            @if ($currentStep > 1)
                <button type="button" wire:click="previousStep" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-primary-600 dark:focus:ring-primary-500">
                    Sebelumnya
                </button>
            @endif

            @if ($currentStep < 2)
                <button type="button" wire:click="nextStep" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:ring-offset-primary-600 @if($currentStep == 1) ms-auto @endif">
                    Selanjutnya
                </button>
            @else
                <button type="submit" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:ring-offset-primary-600 ms-auto">
                    Kirim Formulir
                </button>
            @endif
        </div>
    </form>
</div>

{{-- This part should ideally be in the layout, but for now, place it here --}}
@push('scripts')
<script>
    // Livewire validation error handling to scroll to top if errors exist
    Livewire.hook('message.processed', (message, component) => {
        if (component.errors && Object.keys(component.errors).length > 0) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
</script>
@endpush