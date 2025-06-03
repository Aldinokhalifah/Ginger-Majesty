<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 max-h-[90vh] flex flex-col">
        <h3 class="text-xl font-semibold text-amber-800">Tambah Goal Baru</h3>
        <div class="overflow-y-auto flex-1 pr-2">
            <form id="tabunganForm" action="{{ route('tabungan.create') }}" method="POST" class="mt-4">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label for="nama_goal" class="block text-gray-700 text-sm">Nama Goal</label>
                        <input type="text" id="nama_goal" name="nama_goal" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan nama goal" required>
                    </div>
                    <div>
                        <label for="tanggal" class="block text-gray-700 text-sm">Target Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="target_jumlah" class="block text-gray-700 text-sm">Target Jumlah (Rp)</label>
                        <input type="number" id="target_jumlah" name="target_jumlah" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan target jumlah" required>
                    </div>
                    <div>
                        <label for="jumlah_uang" class="block text-gray-700 text-sm">Jumlah Uang (Rp)</label>
                        <input type="number" id="jumlah_uang" name="jumlah_uang" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Masukkan jumlah uang sekarang" required>
                    </div>
                    <div>
                        <label for="kategori" class="block text-gray-700 text-sm">Kategori</label>
                        <input type="text" id="kategori" name="kategori" class="w-full p-2 border rounded-lg" placeholder="Masukkan atau pilih kategori tabungan" list="kategoriList" required>
                        
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
                        <textarea id="keterangan" name="keterangan" rows="2" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-amber-400 focus:border-transparent" placeholder="Tambahkan keterangan (opsional)"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-4 pt-2 border-t">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600">Batal</button>
                    <button type="submit" class="bg-amber-500 text-white py-2 px-4 rounded-lg hover:bg-amber-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>