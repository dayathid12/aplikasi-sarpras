<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Kendaraan - Universitas Padjadjaran</title>

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        unpad: {
                            orange: '#F7941E', // Unpad Orange nuance
                            blue: '#005b9f',   // Unpad Blue nuance
                            dark: '#0f172a',
                        }
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'fade-in': 'fadeIn 0.3s ease-out forwards',
                        'scale-in': 'scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(15px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.95)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* NEW: Modern Animated Background */
        .bg-gradient-animate {
            background: linear-gradient(-45deg, #f8fafc, #eff6ff, #e0f2fe, #f0f9ff);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* NEW: Floating Blobs */
        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 10s infinite ease-in-out;
        }
        .blob-1 {
            top: -10%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: rgba(0, 91, 159, 0.4); /* Unpad Blue */
            animation-delay: 0s;
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        }
        .blob-2 {
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(247, 148, 30, 0.3); /* Unpad Orange */
            animation-delay: -2s;
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }
        .blob-3 {
            top: 40%;
            left: 40%;
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.25); /* Indigo accent */
            animation-delay: -4s;
            border-radius: 40% 50% 30% 60%;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -50px) rotate(10deg); }
            66% { transform: translate(-20px, 20px) rotate(-5deg); }
        }

        /* NEW: Dot Pattern Overlay */
        .bg-dots {
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.4;
        }

        /* Glassmorphism Card (Updated for better contrast) */
        .tech-card {
            background: rgba(255, 255, 255, 0.9); /* Slightly more opaque */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.05),
                0 8px 10px -6px rgba(0, 0, 0, 0.01),
                inset 0 0 0 1px rgba(255, 255, 255, 0.8);
        }

        /* Glassmorphism Card */
        .tech-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.02),
                0 2px 4px -1px rgba(0, 0, 0, 0.02),
                inset 0 0 0 1px rgba(255, 255, 255, 0.6);
        }

        /* Input Styling */
        .tech-input {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .tech-input:focus {
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        .tech-input.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        .tech-input:disabled {
            background-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
        }

        /* Nav Button Active State */
        .nav-btn.active {
            background-color: #eff6ff;
            color: #2563eb;
        }
        .nav-btn.active i {
            color: #2563eb;
            opacity: 1;
        }

        /* Step Panels */
        .step-panel { display: none; }
        .step-panel.active { display: block; animation: fadeUp 0.5s forwards; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 font-sans pb-12 relative overflow-x-hidden selection:bg-blue-200 selection:text-blue-900 bg-gradient-animate">

    <!-- BACKGROUND: ANIMATED BLOBS & DOTS -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <!-- Dot Pattern -->
        <div class="absolute inset-0 bg-dots"></div>

        <!-- Animated Blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="max-w-5xl mx-auto pt-8 px-4 sm:px-6 relative z-10">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 animate-fade-up mt-8">
            <div class="flex items-start gap-4">
                <div class="w-40 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center p-1 mt-1">
                    <!-- Placeholder Logo Unpad -->
                    <img src="{{ asset('images/Unpad_logo.png') }}" alt="Unpad" class="w-full h-auto object-contain">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Peminjaman Kendaraan</h1>
                    <div class="flex items-center text-xs text-slate-500 font-semibold uppercase tracking-wider mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        Universitas Padjadjaran
                    </div>
                </div>
            </div>

            <!-- Mobile Step Counter (Visual only) -->
            <div class="mt-4 md:mt-0 flex items-center bg-white/80 backdrop-blur px-5 py-2.5 rounded-full border border-slate-200/60 shadow-sm">
                <span class="text-[10px] font-bold text-slate-400 mr-3 uppercase tracking-wider">Progress</span>
                <div class="flex items-center gap-1.5">
                    <div class="step-dot w-2 h-2 rounded-full bg-blue-600 transition-all duration-300" data-step="1"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-300" data-step="2"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-300" data-step="3"></div>
                    <div class="step-dot w-2 h-2 rounded-full bg-slate-200 transition-all duration-300" data-step="4"></div>
                </div>
                <span class="text-sm font-bold text-slate-800 ml-3 w-20 text-right" id="stepLabel">Perjalanan</span>
            </div>
        </div>

        <!-- MAIN CONTENT LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- SIDEBAR NAVIGATION (Desktop) -->
            <div class="hidden lg:block lg:col-span-3 animate-fade-up" style="animation-delay: 0.1s;">
                <div class="sticky top-8">
                    <!-- Added Container Wrapper -->
                    <div class="tech-card rounded-3xl p-5 shadow-xl shadow-slate-200/50">
                        <nav class="space-y-2" id="sidebarNav">
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn active" data-target="1">
                                <i class="ph-bold ph-map-pin text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Perjalanan</p>
                                    <p class="text-[10px] opacity-70 font-medium">Waktu & Lokasi</p>
                                </div>
                            </button>
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn text-slate-500 hover:bg-slate-50" data-target="2">
                                <i class="ph-bold ph-user text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Pengguna</p>
                                    <p class="text-[10px] opacity-70 font-medium">Data Diri & Tim</p>
                                </div>
                            </button>
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn text-slate-500 hover:bg-slate-50" data-target="3">
                                <i class="ph-bold ph-clipboard-text text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Detail</p>
                                    <p class="text-[10px] opacity-70 font-medium">Keterangan Lain</p>
                                </div>
                            </button>
                            <button class="w-full flex items-center group p-3.5 rounded-xl transition-all nav-btn text-slate-500 hover:bg-slate-50" data-target="4">
                                <i class="ph-bold ph-check-circle text-xl mr-3 text-slate-400 transition-colors"></i>
                                <div class="text-left">
                                    <p class="text-sm font-bold">Konfirmasi</p>
                                    <p class="text-[10px] opacity-70 font-medium">Finalisasi Data</p>
                                </div>
                            </button>
                        </nav>

                        <!-- Divider line -->
                        <div class="my-5 border-t border-slate-100"></div>

                        <!-- Help Widget inside Container -->
                        <div class="p-5 bg-slate-900 rounded-2xl text-white shadow-lg relative overflow-hidden group">
                            <div class="absolute -right-4 -top-4 text-slate-800 opacity-20 transform group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-car text-8xl"></i>
                            </div>
                            <p3 class="text-[10px] font-bold text-blue-400 mb-1 uppercase tracking-wider">Pusat Bantuan</p3>
                                                        <p class="text-xs text-slate-300 leading-relaxed mb-4">Informasi lebih lanjut hubungi kontak di bawah.</p>
                            <a href="https://api.whatsapp.com/send/?phone=62812121&text&type=phone_number&app_absent=0" target="_blank" class="inline-flex items-center text-xs font-bold bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg transition-colors">
                                <i class="ph-bold ph-whatsapp-logo mr-2"></i> Hubungi Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORM CARD -->
            <div class="lg:col-span-9 animate-fade-up" style="animation-delay: 0.2s;">
                <div class="tech-card rounded-3xl p-6 sm:p-10 min-h-[550px] flex flex-col relative shadow-xl shadow-slate-200/50">

                    <form id="mainForm" class="flex-1 flex flex-col h-full" onsubmit="event.preventDefault()">
                        <!-- Note: @csrf Removed for static HTML demo -->

                        <!-- STEP 1: PERJALANAN -->
                        <div class="step-panel active" data-step="1">
                            <div class="mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-2xl font-bold text-slate-900">Detail Perjalanan</h2>
                                <p class="text-sm text-slate-500 mt-1">Lengkapi informasi dasar jadwal penggunaan kendaraan.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Waktu Keberangkatan <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="waktu_keberangkatan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-semibold text-slate-700" required>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg flex items-center"><i class="ph-bold ph-warning mr-1"></i> Wajib diisi</p>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Perkiraan Pulang</label>
                                    <input type="datetime-local" name="waktu_kepulangan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-semibold text-slate-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Lokasi Jemput <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-map-pin absolute left-4 top-3.5 text-slate-400 text-lg"></i>
                                        <input type="text" name="lokasi_keberangkatan" placeholder="Cth: Gedung Rektorat" class="tech-input w-full pl-11 pr-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Jumlah Penumpang <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-users absolute left-4 top-3.5 text-slate-400 text-lg"></i>
                                        <input type="number" name="jumlah_rombongan" min="1" placeholder="0" class="tech-input w-full pl-11 pr-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Min 1 orang</p>
                                </div>
                            </div>

                            <div class="mb-6 group">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Alamat Tujuan Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="alamat_tujuan" rows="2" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 resize-none" placeholder="Jalan, Nomor, Gedung..." required></textarea>
                                <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Jenis Kegiatan <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="nama_kegiatan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 appearance-none cursor-pointer" required>
                                            <option value="">Pilih Jenis...</option>
                                            <option value="Perjalanan Dinas">Perjalanan Dinas</option>
                                            <option value="Kuliah Lapangan">Kuliah Lapangan</option>
                                            <option value="Kunjungan Industri">Kunjungan Industri</option>
                                            <option value="Kegiatan Perlombaan">Kegiatan Perlombaan</option>
                                            <option value="Kegiatan Kemahasiswaan">Kegiatan Kemahasiswaan</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Kota Tujuan <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="tujuan_wilayah_id" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 appearance-none cursor-pointer" required>
                                            <option value="">Pilih Kota...</option>
                                            <!-- Static Options for Demo -->
                                            <option value="1">Bandung</option>
                                            <option value="2">Sumedang</option>
                                            <option value="3">Jakarta</option>
                                            <option value="4">Luar Kota Lainnya</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: IDENTITAS -->
                        <div class="step-panel" data-step="2">
                            <div class="mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-2xl font-bold text-slate-900">Identitas Peminjam</h2>
                                <p class="text-sm text-slate-500 mt-1">Data penanggung jawab peminjaman.</p>
                            </div>

                            <div class="space-y-6">
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Unit Kerja / Fakultas <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="unit_kerja_id" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 appearance-none cursor-pointer" required>
                                            <option value="">Pilih Unit...</option>
                                            <option value="1">Fakultas MIPA</option>
                                            <option value="2">Fakultas Hukum</option>
                                            <option value="3">Fakultas Ekonomi Bisnis</option>
                                            <option value="4">Rektorat</option>
                                            <option value="5">Lainnya</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" id="inputNama" name="nama_pengguna" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                        <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                    </div>
                                    <div class="group">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                                        <input type="tel" id="inputKontak" name="kontak_pengguna" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700" required>
                                        <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                    </div>
                                </div>

                                <!-- Box Perwakilan -->
                                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 mt-4 transition-all">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-sm font-bold text-slate-800 flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                                                <i class="ph-bold ph-shield-check"></i>
                                            </div>
                                            Perwakilan Lapangan
                                        </h3>
                                        <label class="inline-flex items-center cursor-pointer group">
                                            <input type="checkbox" id="useSameInfo" class="sr-only peer">
                                            <div class="relative w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                                            <span class="ms-3 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">Sama dengan peminjam</span>
                                        </label>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="group">
                                            <input type="text" id="inputNamaWakil" name="nama_personil_perwakilan" placeholder="Nama Perwakilan" class="tech-input w-full px-4 py-3 rounded-xl text-sm" required>
                                            <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                        </div>
                                        <div class="group">
                                            <input type="tel" id="inputKontakWakil" name="kontak_pengguna_perwakilan" placeholder="Kontak Perwakilan" class="tech-input w-full px-4 py-3 rounded-xl text-sm" required>
                                            <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Wajib diisi</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Status Pemohon <span class="text-red-500">*</span></label>
                                    <div class="flex flex-wrap gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Mahasiswa" class="peer sr-only" required>
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Mahasiswa</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Dosen" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Dosen</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Staf" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Staf</div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status_sebagai" value="Lainnya" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all hover:bg-slate-50">Lainnya</div>
                                        </label>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: DETAIL -->
                        <div class="step-panel" data-step="3">
                            <div class="mb-8 pb-4 border-b border-slate-100">
                                <h2 class="text-2xl font-bold text-slate-900">Detail Tambahan</h2>
                                <p class="text-sm text-slate-500 mt-1">Informasi pendukung kegiatan.</p>
                            </div>

                            <div class="space-y-6">
                                <div class="p-4 bg-orange-50 border-l-4 border-orange-400 rounded-r-lg flex items-start">
                                    <i class="ph-fill ph-info text-orange-500 text-lg mr-3 mt-0.5"></i>
                                    <p class="text-xs text-orange-900 leading-relaxed">
                                        <span class="font-bold">Konfirmasi Wilayah:</span> Sistem membutuhkan verifikasi ulang kota tujuan untuk perhitungan estimasi BBM dan Driver.
                                    </p>
                                </div>

                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Verifikasi Kota Tujuan <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="tujuan_wilayah_id_step3" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 appearance-none cursor-pointer" required>
                                            <option value="">Pilih Kota...</option>
                                            <option value="1">Bandung</option>
                                            <option value="2">Sumedang</option>
                                            <option value="3">Jakarta</option>
                                            <option value="4">Luar Kota Lainnya</option>
                                        </select>
                                        <i class="ph-bold ph-check-circle absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <p class="text-red-500 text-[10px] mt-1 hidden error-msg">Pilih satu</p>
                                </div>

                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Uraian Kegiatan</label>
                                    <textarea name="uraian_singkat_kegiatan" rows="4" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700 resize-none" placeholder="Jelaskan secara singkat agenda kegiatan..."></textarea>
                                </div>
                                <div class="group">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Catatan Khusus</label>
                                    <input type="text" name="catatan_keterangan_tambahan" class="tech-input w-full px-4 py-3 rounded-xl text-sm font-medium text-slate-700" placeholder="Opsional (cth: Membawa alat berat, butuh bagasi luas)">
                                </div>
                            </div>
                        </div>

                        <!-- STEP 4: KONFIRMASI -->
                        <div class="step-panel" data-step="4">
                            <div class="mb-6 pb-4 border-b border-slate-100 text-center">
                                <h2 class="text-2xl font-bold text-slate-900">Konfirmasi Data</h2>
                                <p class="text-sm text-slate-500 mt-1">Pastikan seluruh data valid sebelum dikirim.</p>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                <div id="confirmationContent" class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 text-sm">
                                    <!-- Content Injected via JS -->
                                </div>
                            </div>

                            <div class="mt-8">
                                <label class="flex items-start gap-4 p-4 border border-slate-200 rounded-xl bg-white cursor-pointer hover:border-blue-400 hover:shadow-md transition-all group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="agreementCheck" class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-blue-600 checked:bg-blue-600">
                                        <i class="ph-bold ph-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 text-xs"></i>
                                    </div>
                                    <span class="text-xs text-slate-600 leading-relaxed select-none">
                                        Saya menyatakan data di atas benar dan bersedia mengikuti <span class="text-blue-600 font-bold hover:underline">SOP Peminjaman Kendaraan</span> yang berlaku di Universitas Padjadjaran.
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- FOOTER BUTTONS -->
                        <div class="mt-auto pt-8 flex items-center justify-between border-t border-slate-100">
                            <button type="button" id="btnPrev" class="hidden px-6 py-3 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors flex items-center">
                                <i class="ph-bold ph-arrow-left mr-2"></i> Kembali
                            </button>

                            <div class="ml-auto">
                                <button type="button" id="btnNext" class="px-8 py-3.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-900/20 hover:bg-slate-800 hover:translate-y-[-2px] transition-all flex items-center gap-2 group">
                                    Lanjut <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>

                                <button type="button" onclick="submitForm()" id="btnSubmit" class="hidden px-8 py-3.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/30 hover:shadow-blue-600/40 hover:scale-[1.02] transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                                    <span id="btnText">Kirim Permohonan</span>
                                    <i id="btnIcon" class="ph-bold ph-paper-plane-right"></i>
                                    <i id="loadingIcon" class="ph-bold ph-spinner animate-spin hidden"></i>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SUCCESS MODAL -->
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Card -->
        <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 text-center relative overflow-hidden transform scale-95 transition-all duration-300 z-10">
            <!-- Decorative Top Bar -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6 text-green-500 animate-[bounce_1s_infinite]">
                <i class="ph-fill ph-check-circle text-5xl"></i>
            </div>

            <h3 class="text-2xl font-bold text-slate-900 mb-2">Berhasil!</h3>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed">Permohonan peminjaman kendaraan Anda telah berhasil dikirim dan sedang diproses.</p>

            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-6 relative group cursor-pointer hover:bg-blue-50 hover:border-blue-100 transition-colors" onclick="copyTicket()">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Tiket ID</p>
                <div class="flex items-center justify-center gap-2">
                    <p id="ticketCode" class="text-xl font-mono font-bold text-slate-800 tracking-widest">TRX-8829</p>
                    <i class="ph-bold ph-copy text-slate-400 group-hover:text-blue-500"></i>
                </div>
                <div id="copyToast" class="absolute -top-8 left-1/2 -translate-x-1/2 bg-black text-white text-[10px] py-1 px-3 rounded opacity-0 transition-opacity">Copied!</div>
            </div>

            <button onclick="location.reload()" class="block w-full py-3.5 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-slate-800 transition-all hover:shadow-lg">
                Selesai
            </button>
        </div>
    </div>

    <script>
        const form = document.getElementById('mainForm');
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const stepLabel = document.getElementById('stepLabel');
        const agreementCheck = document.getElementById('agreementCheck');

        let currentStep = 1;
        const totalSteps = 4;
        const stepNames = {1: 'Perjalanan', 2: 'Pengguna', 3: 'Detail', 4: 'Konfirmasi'};

        // Definisi field yang wajib diisi per step
        const requiredFields = {
            1: ['waktu_keberangkatan', 'lokasi_keberangkatan', 'jumlah_rombongan', 'alamat_tujuan', 'nama_kegiatan', 'tujuan_wilayah_id'],
            2: ['unit_kerja_id', 'nama_pengguna', 'kontak_pengguna', 'nama_personil_perwakilan', 'kontak_pengguna_perwakilan', 'status_sebagai'],
            3: ['tujuan_wilayah_id_step3'],
            4: []
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateUI();

            // Logic Checkbox "Sama dengan peminjam"
            const checkSameInfo = document.getElementById('useSameInfo');
            const inputNama = document.getElementById('inputNama');
            const inputKontak = document.getElementById('inputKontak');
            const inputNamaWakil = document.getElementById('inputNamaWakil');
            const inputKontakWakil = document.getElementById('inputKontakWakil');

            checkSameInfo.addEventListener('change', function() {
                if(this.checked) {
                    inputNamaWakil.value = inputNama.value;
                    inputKontakWakil.value = inputKontak.value;
                    inputNamaWakil.setAttribute('readonly', true);
                    inputKontakWakil.setAttribute('readonly', true);
                    inputNamaWakil.classList.add('bg-slate-100', 'text-slate-500');
                    inputKontakWakil.classList.add('bg-slate-100', 'text-slate-500');
                } else {
                    inputNamaWakil.value = '';
                    inputKontakWakil.value = '';
                    inputNamaWakil.removeAttribute('readonly');
                    inputKontakWakil.removeAttribute('readonly');
                    inputNamaWakil.classList.remove('bg-slate-100', 'text-slate-500');
                    inputKontakWakil.classList.remove('bg-slate-100', 'text-slate-500');
                }
            });

            // Update live jika checkbox checked dan input utama berubah
            [inputNama, inputKontak].forEach(input => {
                input.addEventListener('input', () => {
                    if(checkSameInfo.checked) {
                        checkSameInfo.dispatchEvent(new Event('change'));
                    }
                });
            });
        });

        // Navigation Logic
        btnNext.addEventListener('click', () => {
            if(validateStep(currentStep)) {
                if(currentStep < totalSteps) {
                    currentStep++;
                    updateUI();
                }
            }
        });

        btnPrev.addEventListener('click', () => {
            if(currentStep > 1) {
                currentStep--;
                updateUI();
            }
        });

        // Direct Sidebar Navigation
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent form submit
                const target = parseInt(btn.dataset.target);
                // Hanya boleh lompat ke step yang sudah dilewati atau next step
                if(target < currentStep || (target === currentStep + 1 && validateStep(currentStep))) {
                    currentStep = target;
                    updateUI();
                }
            });
        });

        function updateUI() {
            // 1. Show/Hide Panels
            document.querySelectorAll('.step-panel').forEach(el => el.classList.remove('active'));
            const activePanel = document.querySelector(`.step-panel[data-step="${currentStep}"]`);
            activePanel.classList.add('active');

            // 2. Update Header Steps
            stepLabel.innerText = stepNames[currentStep];
            document.querySelectorAll('.step-dot').forEach((el, idx) => {
                if((idx + 1) <= currentStep) {
                    el.classList.remove('bg-slate-200');
                    el.classList.add('bg-blue-600', 'scale-125', 'ring-4', 'ring-blue-100');
                } else {
                    el.classList.remove('bg-blue-600', 'scale-125', 'ring-4', 'ring-blue-100');
                    el.classList.add('bg-slate-200');
                }
            });

            // 3. Update Sidebar Active State
            document.querySelectorAll('.nav-btn').forEach(btn => {
                const step = parseInt(btn.dataset.target);
                if(step === currentStep) {
                    btn.classList.add('active', 'bg-blue-50', 'text-blue-600');
                    btn.querySelector('i').classList.remove('text-slate-400');
                    btn.querySelector('i').classList.add('text-blue-600');
                } else {
                    btn.classList.remove('active', 'bg-blue-50', 'text-blue-600');
                    btn.querySelector('i').classList.add('text-slate-400');
                    btn.querySelector('i').classList.remove('text-blue-600');
                }
            });

            // 4. Button Visibility
            if(currentStep === 1) {
                btnPrev.classList.add('hidden');
            } else {
                btnPrev.classList.remove('hidden');
            }

            if(currentStep === totalSteps) {
                btnNext.classList.add('hidden');
                btnSubmit.classList.remove('hidden');
                generateSummary(); // Generate summary when reaching step 4
            } else {
                btnNext.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
            }

            // Scroll to top of card smoothly
            document.querySelector('.tech-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function validateStep(step) {
            let isValid = true;
            const fields = requiredFields[step];

            fields.forEach(fieldName => {
                // Handle Radio Buttons
                if(fieldName === 'status_sebagai') {
                    const radios = document.getElementsByName(fieldName);
                    let radioChecked = false;
                    radios.forEach(r => { if(r.checked) radioChecked = true; });
                    const container = radios[0].closest('.group').querySelector('.error-msg');

                    if(!radioChecked) {
                        isValid = false;
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                }
                // Handle Standard Inputs
                else {
                    const input = document.getElementsByName(fieldName)[0];
                    if(!input) return;

                    const errorMsg = input.parentElement.querySelector('.error-msg') || input.parentElement.parentElement.querySelector('.error-msg');

                    if(!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                        if(errorMsg) errorMsg.classList.remove('hidden');
                    } else {
                        input.classList.remove('error');
                        if(errorMsg) errorMsg.classList.add('hidden');
                    }
                }
            });

            return isValid;
        }

        function generateSummary() {
            const formData = new FormData(form);
            const summaryContainer = document.getElementById('confirmationContent');

            // Helper to get text from select options
            const getSelectText = (name) => {
                const el = document.getElementsByName(name)[0];
                return el.options[el.selectedIndex]?.text || '-';
            };

            const data = {
                'Waktu Berangkat': formData.get('waktu_keberangkatan').replace('T', ' '),
                'Lokasi Jemput': formData.get('lokasi_keberangkatan'),
                'Tujuan': formData.get('alamat_tujuan'),
                'Kota': getSelectText('tujuan_wilayah_id'),
                'Kegiatan': formData.get('nama_kegiatan'),
                'Penumpang': formData.get('jumlah_rombongan') + ' Orang',
                'Peminjam': formData.get('nama_pengguna'),
                'Kontak': formData.get('kontak_pengguna'),
                'Perwakilan': formData.get('nama_personil_perwakilan'),
                'Kontak Wakil': formData.get('kontak_pengguna_perwakilan')
            };

            let html = '';
            for (const [key, value] of Object.entries(data)) {
                html += `
                    <div class="border-b border-slate-100 pb-2">
                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-1">${key}</p>
                        <p class="font-semibold text-slate-800 break-words">${value || '-'}</p>
                    </div>
                `;
            }
            summaryContainer.innerHTML = html;
        }

        function submitForm() {
            if(!agreementCheck.checked) {
                alert('Mohon setujui pernyataan terlebih dahulu.');
                return;
            }

            // Simulate Loading
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const loadingIcon = document.getElementById('loadingIcon');

            btnSubmit.disabled = true;
            btnText.innerText = 'Mengirim...';
            btnIcon.classList.add('hidden');
            loadingIcon.classList.remove('hidden');

            // Simulate API Call delay
            setTimeout(() => {
                // Show Success Modal
                const modal = document.getElementById('successModal');
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.querySelector('div[class*="scale-95"]').classList.remove('scale-95');
                modal.querySelector('div[class*="scale-95"]').classList.add('scale-100');

                // Generate Random Ticket
                document.getElementById('ticketCode').innerText = 'TRX-' + Math.floor(1000 + Math.random() * 9000);
            }, 1500);
        }

        function copyTicket() {
            const ticket = document.getElementById('ticketCode').innerText;
            navigator.clipboard.writeText(ticket);

            const toast = document.getElementById('copyToast');
            toast.classList.remove('opacity-0');
            setTimeout(() => toast.classList.add('opacity-0'), 2000);
        }
    </script>
</body>
</html>
