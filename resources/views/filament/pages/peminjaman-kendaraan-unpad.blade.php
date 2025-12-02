<div class="min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 p-4 sm:p-6 lg:p-8">
    <div x-data="{ step: 1 }" class="max-w-4xl mx-auto">

        {{-- Stepper Navigation with Glassmorphism Effect --}}
        <div class="relative mb-8 p-4 bg-white/30 backdrop-blur-xl border border-white/40 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between">
                {{-- Step 1 --}}
                <div class="flex-1 text-center cursor-pointer" @click="step = 1">
                    <div :class="{'animate-pulse': step === 1}" class="mx-auto w-12 h-12 flex items-center justify-center rounded-full transition-all duration-300"
                         :class="step >= 1 ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-400'">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <p class="mt-2 text-xs sm:text-sm font-bold" :class="step >= 1 ? 'text-blue-700' : 'text-gray-500'">Perjalanan</p>
                </div>

                {{-- Connector --}}
                <div class="flex-1 h-1 bg-gray-200 rounded-full"><div class="h-1 rounded-full bg-blue-600" :style="`width: ${step > 1 ? '100%' : '0%'}`"></div></div>

                {{-- Step 2 --}}
                <div class="flex-1 text-center cursor-pointer" @click="step = 2">
                    <div :class="{'animate-pulse': step === 2}" class="mx-auto w-12 h-12 flex items-center justify-center rounded-full transition-all duration-300"
                         :class="step >= 2 ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-400'">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <p class="mt-2 text-xs sm:text-sm font-bold" :class="step >= 2 ? 'text-blue-700' : 'text-gray-500'">Pengguna</p>
                </div>

                {{-- Connector --}}
                <div class="flex-1 h-1 bg-gray-200 rounded-full"><div class="h-1 rounded-full bg-blue-600" :style="`width: ${step > 2 ? '100%' : '0%'}`"></div></div>

                {{-- Step 3 --}}
                <div class="flex-1 text-center cursor-pointer" @click="step = 3">
                    <div :class="{'animate-pulse': step === 3}" class="mx-auto w-12 h-12 flex items-center justify-center rounded-full transition-all duration-300"
                         :class="step >= 3 ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-400'">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="mt-2 text-xs sm:text-sm font-bold" :class="step >= 3 ? 'text-blue-700' : 'text-gray-500'">Detail</p>
                </div>

                {{-- Connector --}}
                <div class="flex-1 h-1 bg-gray-200 rounded-full"><div class="h-1 rounded-full bg-blue-600" :style="`width: ${step > 3 ? '100%' : '0%'}`"></div></div>

                {{-- Step 4 --}}
                <div class="flex-1 text-center cursor-pointer" @click="step = 4">
                    <div :class="{'animate-pulse': step === 4}" class="mx-auto w-12 h-12 flex items-center justify-center rounded-full transition-all duration-300"
                         :class="step >= 4 ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-400'">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="mt-2 text-xs sm:text-sm font-bold" :class="step >= 4 ? 'text-blue-700' : 'text-gray-500'">Dokumen</p>
                </div>
            </div>
        </div>

        {{-- Form Content Card --}}
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-2xl transition-all duration-500">
            {{ $this->form }}
        </div>

    </div>
</div>
