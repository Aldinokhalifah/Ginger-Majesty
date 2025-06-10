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

    <!-- Spending Alert -->
    @if($alertStatus['show'])
    <div id="spendingAlert" class="fixed inset-0 flex items-center justify-center z-50">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Alert Content -->
        <div class="container relative bg-white rounded-lg shadow-lg border-l-4 {{ $alertStatus['severity'] === 'danger' ? 'border-red-500' : 'border-amber-500' }} max-w-sm mx-4">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @if($alertStatus['severity'] === 'danger')
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        @else
                            <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <h3 class="text-sm font-medium {{ $alertStatus['severity'] === 'danger' ? 'text-red-800' : 'text-amber-800' }}">
                            Peringatan Pengeluaran
                        </h3>
                        <div class="mt-2 text-sm {{ $alertStatus['severity'] === 'danger' ? 'text-red-700' : 'text-amber-700' }}">
                            <p>
                                Pengeluaran Anda telah mencapai {{ $alertStatus['percentage'] }}% dari total pemasukkan.
                                @if($alertStatus['severity'] === 'danger')
                                    Segera kurangi pengeluaran!
                                @else
                                    Pertimbangkan untuk mengurangi pengeluaran.
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button onclick="closeAlert()" class="inline-flex text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 z-50 transition duration-200 ease-in-out lg:relative lg:flex">
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
                                    @forelse ($latestPemasukkan as $pemasukkan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ \Carbon\Carbon::parse($pemasukkan->tanggal)->format('d M Y') }}</td>
                                            <td class="px-4 py-2">
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ $pemasukkan->kategori }}</span>
                                                    <p class="text-sm text-gray-600">{{ $pemasukkan->keterangan }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-right text-green-600 font-medium">+Rp {{ number_format($pemasukkan->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-8 text-gray-500">
                                                Belum ada pemasukkan terbaru!
                                            </td>
                                        </tr>
                                    @endforelse
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
                                    @forelse ($latestPengeluaran as $pengeluaran)
                                        <tr class="hover:bg-gray-50 overflow-x-auto">
                                            <td class="px-4 py-2 text-sm text-gray-600">{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d M Y') }}</td>
                                            <td class="px-4 py-2">
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ $pengeluaran->kategori }}</span>
                                                    <p class="text-sm text-gray-600">{{ $pengeluaran->keterangan }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-right text-red-600 font-medium">-Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-8 text-gray-500">
                                                Belum ada pengeluaran terbaru!
                                            </td>
                                        </tr>
                                    @endforelse
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
            const dashboardChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Pemasukkan', 'Total Pengeluaran'],
                    datasets: [{
                        data: [
                            {{ $chartData['Total Pemasukkan'] ?? 0 }},
                            {{ $chartData['Total Pengeluaran'] ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.5)',
                            'rgba(239, 68, 68, 0.5)'
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
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            ticks: {
                                // Rotasi hanya untuk mobile
                                maxRotation: window.innerWidth < 768 ? 45 : 0,
                                minRotation: window.innerWidth < 768 ? 45 : 0,
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                },
                                callback: function(value, index, values) {
                                    const label = this.getLabelForValue(value);
                                    // Mempersingkat label untuk layar mobile
                                    if (window.innerWidth < 768) {
                                        const labels = ['Pemasukkan', 'Pengeluaran'];
                                        return labels[index];
                                    }
                                    return label;
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                },
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
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

         // Alert functions
        function closeAlert() {
            const alert = document.getElementById('spendingAlert');
            if (alert) {
                alert.classList.add('opacity-0', 'transition-opacity');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }
        }

        // Auto-hide alert after 10 seconds
        setTimeout(() => {
            closeAlert();
        }, 10000);

        window.addEventListener('resize', function() {
            dashboardChart.options.scales.x.ticks.maxRotation = window.innerWidth < 768 ? 45 : 0;
            dashboardChart.options.scales.x.ticks.minRotation = window.innerWidth < 768 ? 45 : 0;
            dashboardChart.update();
        });
    </script>
</body>
</html>

