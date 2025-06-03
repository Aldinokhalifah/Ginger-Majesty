{{-- <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-[90vh] flex flex-col">
        <h3 class="text-xl font-semibold text-amber-800">Tambah Goal Baru</h3>
        <div class="overflow-y-auto flex-1 pr-2">
            <form id="tabunganForm" action="{{ route('tabungan.update', $tabungan->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')

                <div class="space-y-3">
                    <div>
                        <label for="nama_goal" class="block text-gray-700 text-sm">Nama Goal</label>
                        <input type="text" id="nama_goal" value="{{ old('nama_goal', $tabungan->nama_goal) }}" name="nama_goal" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan nama goal" required>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-gray-700 text-sm">Target Tanggal</label>
                        <input type="date" id="tanggal" value="{{ old('tanggal', $tabungan->tanggal) }}" name="tanggal" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="target_jumlah" class="block text-gray-700 text-sm">Target Jumlah (Rp)</label>
                        <input type="number" id="target_jumlah" value="{{ old('target_jumlah', $tabungan->target_jumlah) }}" name="target_jumlah" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan target jumlah" required>
                    </div>
                    <div>
                        <label for="jumlah_uang" class="block text-gray-700 text-sm">Jumlah Uang (Rp)</label>
                        <input type="number" id="jumlah_uang" value="{{ old('jumlah_uang', $tabungan->jumlah_uang) }}" name="jumlah_uang" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan jumlah uang sekarang" required>
                    </div>
                    <div>
                        <label for="kategori" class="block text-gray-700 text-sm">Kategori</label>
                        <input type="text" id="kategori" value="{{ old('kategori', $tabungan->kategori) }}" name="kategori" class="w-full p-2 border rounded-lg" placeholder="Masukkan atau pilih kategori tabungan" list="kategoriList" required>
                        
                        @if(isset($ambilKategori) && is_countable($ambilKategori) && count($ambilKategori) > 0)
                            <datalist id="kategoriList">
                                @foreach ($ambilKategori as $kategori)
                                    <option value="{{ $kategori->kategori }}">
                                @endforeach
                            </datalist>
                        @endif
                    </div>
                    <div>
                        <label for="keterangan" class="block text-gray-700 text-sm">Keterangan</label>
                        <textarea id="keterangan" value="{{ old('keterangan', $tabungan->keterangan) }}" name="keterangan" rows="2" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Tambahkan keterangan (opsional)"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-4 pt-2 border-t">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white py-2 px-4 rounded-lg hover:bg-amber-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}


@props(['tabungan'])

<div id="modal-edit-{{ $tabungan->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-[90vh] flex flex-col">
        <h3 class="text-xl font-semibold text-amber-800">Edit Goal</h3>
        <div class="overflow-y-auto flex-1 pr-2">
            <form action="{{ route('tabungan.update', $tabungan->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                
                <div class="space-y-3">
                    <div>
                        <label for="nama_goal_{{ $tabungan->id }}" class="block text-gray-700 text-sm">Nama Goal</label>
                        <input type="text" id="nama_goal_{{ $tabungan->id }}" value="{{ old('nama_goal', $tabungan->nama_goal) }}" name="nama_goal" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan nama goal" required>
                    </div>
                    <div>
                        <label for="tanggal_{{ $tabungan->id }}" class="block text-gray-700 text-sm">Target Tanggal</label>
                        <input type="date" id="tanggal_{{ $tabungan->id }}" value="{{ old('tanggal', $tabungan->tanggal) }}" name="tanggal" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="target_jumlah" class="block text-gray-700 text-sm">Target Jumlah (Rp)</label>
                        <input type="number" id="target_jumlah" value="{{ old('target_jumlah', $tabungan->target_jumlah) }}" name="target_jumlah" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan target jumlah" required>
                    </div>
                    <div>
                        <label for="jumlah_uang" class="block text-gray-700 text-sm">Jumlah Uang (Rp)</label>
                        <input type="number" id="jumlah_uang" value="{{ old('jumlah_uang', $tabungan->jumlah_uang) }}" name="jumlah_uang" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan jumlah uang sekarang" required>
                    </div>
                    <div>
                        <label for="kategori" class="block text-gray-700 text-sm">Kategori</label>
                        <input type="text" id="kategori" value="{{ old('kategori', $tabungan->kategori) }}" name="kategori" class="w-full p-2 border rounded-lg" placeholder="Masukkan atau pilih kategori tabungan" list="kategoriList" required>
                        
                        @if(isset($ambilKategori) && is_countable($ambilKategori) && count($ambilKategori) > 0)
                            <datalist id="kategoriList">
                                @foreach ($ambilKategori as $kategori)
                                    <option value="{{ $kategori->kategori }}">
                                @endforeach
                            </datalist>
                        @endif
                    </div>
                    <div>
                        <label for="keterangan" class="block text-gray-700 text-sm">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="2" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Tambahkan keterangan (opsional)">{{ old('keterangan', $tabungan->keterangan) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="hideModal('modal-edit-{{ $tabungan->id }}')" class="mr-2 px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>