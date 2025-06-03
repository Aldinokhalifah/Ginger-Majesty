<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo .jpg') }}" />
    <title>Dashboard Tabungan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"></script>
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
                <h2 class="text-2xl font-bold text-gray-800 text-pretty">Dashboard Tabungan</h2>
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
            
            <!-- Chart Tabungan -->
            <div class="p-4 md:p-6 max-w-6xl mx-auto">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                  <h1 class="text-2xl font-bold text-amber-800">Tabungan & Goals</h1>
                  <button id="tambahGoalBtn" class="bg-amber-500 hover:bg-amber-600 shadow-lg shadow-amber-500 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors w-full sm:w-auto justify-center">
                    <i data-feather="plus-circle" class="w-4 h-4"></i>
                    <span>Tambah Goal Baru</span>
                  </button>
                </div>

              <!-- Modal Form Tambah Goal -->
              <x-modal_tambah_goals/>
                <!-- Ringkasan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                  <div class="bg-amber-50 rounded-xl p-6 shadow-md border border-amber-100">
                      <div class="flex justify-between items-center mb-4">
                          <h2 class="text-lg font-semibold text-amber-800">Total Tabungan</h2>
                          <i data-feather="pie-chart" class="w-5 h-5 text-amber-500"></i>
                      </div>
                      <p class="text-3xl font-bold text-amber-700">Rp {{ number_format($totalTabungan, 0, ',', '.') }}</p>
                        <div class="mt-2 text-amber-600 text-sm flex items-center gap-1">
                          <i data-feather="target" class="w-4 h-4"></i>
                          <span>{{ $tabungan->count() }} {{ Str::plural('goal', $tabungan->count()) }} aktif saat ini</span>
                        </div>
                  </div>
                  <div class="bg-amber-50 rounded-xl p-6 shadow-md border border-amber-100">
                      <div class="flex justify-between items-center mb-4">
                          <h2 class="text-lg font-semibold text-amber-800">Progress Rata-rata</h2>
                          <i data-feather="trending-up" class="w-5 h-5 text-amber-500"></i>
                      </div>
                      <p class="text-3xl font-bold text-amber-700">{{ $averageProgress }}%</p>
                      <div class="mt-2 text-amber-600 text-sm">{{ $averageProgress >= 50 ? 'Anda di jalur yang tepat!' : 'Terus semangat menabung!' }}</div>
                  </div>
                </div>
            
                <!-- Filter dan Sort -->
                <div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                  <h2 class="text-xl font-semibold text-amber-800">Goals Keuangan Anda</h2>
                  <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <select class="bg-white border border-amber-200 text-amber-800 rounded-lg px-3 py-2 text-sm w-full">
                      <option>Semua Kategori</option>
                      @foreach ($ambilKategori as $kategori)
                      <option value="{{ $kategori->kategori }}">{{ $kategori->kategori }}</option>
                    @endforeach
                    </select>
                    <select class="bg-white border border-amber-200 text-amber-800 rounded-lg px-3 py-2 text-sm text-center w-full">
                      <option>Urutkan: Terbaru</option>
                      <option>Urutkan: Deadline Terdekat</option>
                      <option>Urutkan: Progress Tertinggi</option>
                    </select>
                  </div>
                </div>
            
                <!-- Goals List -->
                <div class="space-y-4">
                  @forelse($tabungan as $goal)
                      <div class="bg-white rounded-xl p-6 shadow-md border border-amber-100 hover:shadow-lg transition-shadow">
                          <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                              <div>
                                  <h3 class="text-lg font-bold text-amber-800">{{ $goal->nama_goal }}</h3>
                                    <div class="text-amber-600 text-sm">Target: {{ \Carbon\Carbon::parse($goal->tanggal)->format('d M Y') }}</div>
                                  <div class="flex gap-1 items-start">
                                    <span class="font-semibold min-w-[35px]">Ket:</span>
                                    <span class="text-sm text-amber-700 break-words">{{ $goal->keterangan }}</span>
                                  </div>
                              </div>
                              <div class="flex gap-2">
                                <button onclick="showModal('modal-edit-{{ $goal->id }}')" class="p-2 rounded-full hover:bg-amber-100 text-amber-500">
                                  <i data-feather="edit" class="w-4 h-4"></i>
                                </button>
                                <x-modal_edit_goals :tabungan="$goal" :ambilKategori="$ambilKategori" />
                                  <form action="{{ route('tabungan.delete', $goal->id) }}" method="POST" class="inline"> 
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="p-2 rounded-full hover:bg-amber-100 text-amber-500">
                                          <i data-feather="trash-2" class="w-4 h-4"></i>
                                      </button>
                                  </form>
                              </div>
                          </div>
              
                          <div class="mb-2">
                                <div class="flex flex-col sm:flex-row justify-between text-sm mb-1 gap-1">
                                  <span class="font-medium text-amber-700 break-all">
                                    Rp {{ number_format($goal->jumlah_uang, 0, ',', '.') }}
                                  </span>
                                  <span class="text-amber-600 break-all">
                                    dari Rp {{ number_format($goal->target_jumlah, 0, ',', '.') }}
                                  </span>
                                </div>
                              <div class="w-full bg-amber-100 rounded-full h-3">
                                  <div class="bg-amber-500 h-3 rounded-full" style="width: {{ $goal->progress }}%"></div>
                              </div>
                          </div>
              
                          <div class="flex justify-between items-center mt-4">
                              <span class="bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full">{{ $goal->kategori }}</span>
                              <span class="font-semibold text-amber-700">{{ $goal->progress }}%</span>
                          </div>
                      </div>
                  @empty
                    <div class="text-center py-8 text-gray-500">
                      Belum ada goal tabungan. Mulai buat goal pertama Anda!
                    </div>
                  @endforelse
                </div>
            
                <!-- Load More Button -->
                <div class="mt-8 text-center">
                  <button class="text-amber-600 hover:text-amber-800 font-medium transition-colors">
                    Lihat Semua Goals
                  </button>
                </div>
              </div>
            
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', () => {
          feather.replace();
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

          document.addEventListener('DOMContentLoaded', () => {
          feather.replace();
          
          // Add modal event listeners
          const tambahGoalBtn = document.getElementById('tambahGoalBtn');
          tambahGoalBtn.addEventListener('click', function(e) {
              e.preventDefault();
              openModal();
            });
          });

           // Modal functions
          function openModal() {
              document.getElementById('modal').classList.remove('hidden');
            }

          function closeModal() {
              document.getElementById('modal').classList.add('hidden');
          }

                function showModal(id) {
              document.getElementById(id).classList.remove('hidden');
          }
          
          function hideModal(id) {
              document.getElementById(id).classList.add('hidden');
          }
    </script>
</body>
</html>
