<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo .jpg') }}" />
    <title>Dashboard Pemasukkan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0&icon_names=delete" />
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
                <h2 class="text-2xl font-bold text-gray-800 text-pretty">Dashboard Pemasukkan</h2>
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
            
            <!-- Chart Pemasukkan -->
            <div class="mt-6">
                <canvas id="pemasukkanChart"></canvas>
            </div>

            <!-- Riwayat Pemasukkan -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-700">Riwayat Pemasukkan Terbaru</h3>
                <ul class="mt-3 space-y-2">
                    @foreach ($latestPemasukkan as $pemasukan)
                        <li class="p-3 bg-gray-50 rounded-lg relative">
                            <form action="{{ route('pemasukkan.delete', $pemasukan->id) }}" method="POST" class="text-gray-400 absolute top-2 right-2">
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
                                    <span class="font-medium text-gray-800">{{ $pemasukan->kategori }}</span>
                                    <span class="text-sm text-slate-600">{{ $pemasukan->created_at->format('d M Y') }}</span>
                                    <div class="flex gap-1 items-start">
                                        <span class="font-semibold min-w-[3rem]">Ket:</span>
                                        <span class="text-sm text-slate-700 break-words">{{ $pemasukan->keterangan ?: '-' }}</span>
                                    </div>
                                </div>
                                <span class="text-green-600 font-semibold whitespace-nowrap self-start sm:self-center mt-2 sm:mt-0">
                                    +Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Button Tambah Pemasukkan -->
            <div class="mt-6 text-right">
                <button onclick="openModal()" class="bg-green-600 text-white py-2 px-4 rounded-lg shadow-lg shadow-green-500 hover:bg-green-700">Tambah Pemasukkan</button>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah Pemasukkan -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-xl font-semibold">Tambah Pemasukkan</h3>
            <form action="{{ route('pemasukkan.create') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="tanggal" class="block text-gray-700">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="w-full p-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="jumlah" class="block text-gray-700">Jumlah (Rp)</label>
                    <input type="number" id="jumlah" name="jumlah" class="w-full p-2 border rounded-lg" placeholder="Masukkan jumlah pemasukkan">
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
                    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script untuk Chart.js dan Modal -->
    <script>
        var ctx = document.getElementById('pemasukkanChart').getContext('2d');
        var pemasukkanChart = new Chart(ctx, {
            type: 'bar', 
            data: {
                labels: {!! json_encode($chartData->keys()) !!}, 
                datasets: [{
                    label: 'Total Pemasukkan',
                    data: {!! json_encode($chartData->values()) !!}, 
                    backgroundColor: 'rgba(34, 197, 94, 0.5)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: { beginAtZero: true },
                },
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
        
        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>
