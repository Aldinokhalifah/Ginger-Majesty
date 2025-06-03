<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo .jpg') }}" />
    <title>Dashboard Pengeluaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition duration-200 ease-in-out lg:relative lg:flex z-50">
        <x-sidenav/>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6 relative z-0">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center w-full">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard Pengeluaran</h2>
                <h2 class="font-semibold text-slate-700 text-lg hidden lg:block">
                    @auth
                        {{ Auth::user()->name }}
                    @endauth
                </h2>
                <!-- Mobile menu button -->
                <button id="menuButton" class="lg:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            
            <!-- Chart Pengeluaran -->
            <div class="mt-6">
                <canvas id="pengeluaranChart"></canvas>
            </div>

            <!-- Riwayat Pengeluaran -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-700">Riwayat Pengeluaran Terbaru</h3>
                <ul class="mt-3 space-y-2">
                    @forelse ($latestPengeluaran as $pengeluaran)
                        <li class="p-3 bg-gray-50 rounded-lg relative">
                            <form action="{{ route('pengeluaran.delete', $pengeluaran->id) }}" method="POST" class="text-gray-400 absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            <div class="flex flex-col sm:flex-row justify-between gap-2 pr-8">
                                <div class="flex flex-col space-y-1">
                                    <span class="font-medium text-gray-800">{{ $pengeluaran->kategori }}</span>
                                    <span class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d M Y') }}</span>
                                    <div class="flex gap-1 items-start">
                                        <span class="font-semibold min-w-[35px]">Ket:</span>
                                        <span class="text-sm text-slate-700 break-words">{{ $pengeluaran->keterangan }}</span>
                                    </div>
                                </div>
                                <span class="text-red-600 font-medium whitespace-nowrap self-start sm:self-center">
                                    -Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        </li>
                        
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            Belum ada pengeluaran. Mulai buat pengeluaran pertama Anda!
                        </div>
                    @endforelse
                </ul>
            </div>

            <!-- Button Tambah Pengeluaran -->
            <div class="mt-6 text-right">
                <button onclick="openModal()" class="bg-red-600 text-white py-2 px-4 rounded-lg shadow-lg shadow-red-500 hover:bg-red-700">Tambah Pengeluaran</button>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah Pengeluaran -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-semibold">Tambah Pengeluaran</h3>
            <form action="{{ route('pengeluaran.create') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="tanggal" class="block text-gray-700">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="w-full p-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="jumlah" class="block text-gray-700">Jumlah (Rp)</label>
                    <input type="number" id="jumlah" name="jumlah" class="w-full p-2 border rounded-lg" placeholder="Masukkan jumlah pengeluaran">
                </div>
                <div class="mb-4">
                    <label for="kategori" class="block text-gray-700">Kategori</label>
                    <input type="text" id="kategori" name="kategori" class="w-full p-2 border rounded-lg" placeholder="Masukkan atau pilih kategori pemasukkan" list="kategoriList">
                    
                    @if(count($ambilKategori) > 0)
                        <datalist id="kategoriList" name="kategoriList">
                            @foreach ($ambilKategori as $kategori)
                                <option value="{{ $kategori->kategori }}">{{ $kategori->kategori }}</option>
                            @endforeach
                        </datalist>
                    @endif
                </div>                
                <div class="mb-4">
                    <label for="keterangan" class="block text-gray-700">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" class="w-full p-2 border rounded-lg" placeholder="Tambahkan keterangan (opsional)"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600">Batal</button>
                    <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script untuk Chart.js dan Modal -->
    <script>
        var ctx = document.getElementById('pengeluaranChart').getContext('2d');
        var pengeluaranChart = new Chart(ctx, {
            type: 'bar',
            data: {
            labels: {!! json_encode($chartData->keys()) !!},
            datasets: [{
                label: 'Total Pengeluaran',
                data: {!! json_encode($chartData->values()) !!},
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                borderRadius: 10
            }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        // Toggle mobile menu
        const menuButton = document.getElementById('menuButton');
        const sidebar = document.getElementById('sidebar');
        
        // Add transition classes for smooth animation
        sidebar.classList.add('transition-transform', 'duration-300', 'ease-in-out');
            
            menuButton.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                // Add overlay for mobile
                if (!sidebar.classList.contains('-translate-x-full')) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300 z-40';
            overlay.onclick = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.remove();
            };
            document.body.appendChild(overlay);
                } else {
            const overlay = document.querySelector('.bg-opacity-50');
            if (overlay) overlay.remove();
                }
        });
    </script>
</body>
</html>
