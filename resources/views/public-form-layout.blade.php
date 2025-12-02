<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Kendaraan Unpad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-3xl w-full p-8 form-container-card">
            <div class="text-center mb-8">
                <img src="{{ asset('images/Unpad_logo.png') }}" alt="Unpad Logo" class="h-20 mx-auto mb-4">
                <h3 class="text-2xl font-bold text-gray-800">Formulir Peminjaman Kendaraan Universitas Padjadjaran</h3>
                <p class="text-gray-500 mt-1">Silakan isi formulir di bawah ini dengan lengkap dan benar.</p>
            </div>

            @yield('content')
        </div>
    </div>

    @livewireScripts
    @filamentScripts
</body>
</html>