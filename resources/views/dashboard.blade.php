<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo .jpg') }}" />
    <title>Dashboard Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 flex">
    

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition duration-200 ease-in-out lg:relative lg:flex">
        <x-sidenav/>
    </div>

    <!-- Main Content -->
    <div class="p-6 w-full">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center w-full">
                <h2 class="text-2xl font-bold text-gray-800 text-pretty">Dashboard Keuangan</h2>
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
            
            <!-- Ringkasan Keuangan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="p-4 bg-green-100 rounded-lg">
                    <p class="text-sm text-gray-600">Total Pemasukan</p>
                    <p class="text-lg font-bold text-green-700">Rp {{ number_format($totalPemasukkan ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-red-100 rounded-lg">
                    <p class="text-sm text-gray-600">Total Pengeluaran</p>
                    <p class="text-lg font-bold text-red-700">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-amber-100 rounded-lg">
                    <p class="text-sm text-gray-600">Saldo Saat Ini</p>
                    <p class="text-lg font-bold text-amber-700">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Grafik Keuangan -->
            <div class="mt-6 w-full" style="height: 300px;">
                <canvas id="dashboardChart"></canvas>
            </div>

            <!-- Daftar Transaksi Terbaru -->
            <div class="mt-6">
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tabel Pemasukkan -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Pemasukkan Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead class="bg-green-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Keterangan</th>
                                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($latestPemasukkan as $pemasukkan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ $pemasukkan->created_at->format('d M Y') }}</td>
                                            <td class="px-4 py-2">
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ $pemasukkan->kategori }}</span>
                                                    <p class="text-sm text-gray-600">{{ $pemasukkan->keterangan }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-right text-green-600 font-medium">+Rp {{ number_format($pemasukkan->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Pengeluaran -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Pengeluaran Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead class="bg-red-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Keterangan</th>
                                        <th class="px-4 py-2 text-right text-sm font-semibold text-gray-600">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($latestPengeluaran as $pengeluaran)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ $pengeluaran->created_at->format('d M Y') }}</td>
                                            <td class="px-4 py-2">
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ $pengeluaran->kategori }}</span>
                                                    <p class="text-sm text-gray-600">{{ $pengeluaran->keterangan }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-right text-red-600 font-medium">-Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Script untuk Chart.js -->
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Pemasukkan', 'Total Pengeluaran'],
                    datasets: [
                        {
                            data: [
                                {{ $chartData['Total Pemasukkan'] ?? 0 }},
                                {{ $chartData['Total Pengeluaran'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.5)',  // Hijau untuk Pemasukkan
                                'rgba(239, 68, 68, 0.5)'   // Merah untuk Pengeluaran
                            ],
                            borderColor: [
                                'rgb(34, 197, 94)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 1,
                            borderRadius: 10,
                            hoverBackgroundColor: [
                                'rgba(34, 197, 94, 0.7)',
                                'rgba(239, 68, 68, 0.7)'
                            ]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide legend since we don't need it
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    return tooltipItems[0].label;
                                },
                                label: function(context) {
                                    const value = context.raw;
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        });

        // Toggle mobile menu
        const menuButton = document.getElementById('menuButton');
        const sidebar = document.getElementById('sidebar');
        
        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>

