<!-- Modal Tambah -->
<div class="ui tiny modal" id="modalTambah">
    <div class="header">Tambah Kategori</div>
    <div class="content">
        <form class="ui form" method="POST">
            <input type="hidden" name="tambah_kategori">
        <div class="field">
            <label>Kategori</label>
            <select name="kategori" class="ui fluid dropdown" required>
                    <option value="">Pilih Kategori</option>
                    <option value="Wajib">Wajib</option>
                    <option value="Optional">Optional</option>
            </select>
        </div>
        <div class="field">
            <label>Sub Kategori</label>
            <input type="text" name="sub_kategori" placeholder="Masukkan Sub Kategori" required>
        </div>
        <button class="fluid ui primary button" type="submit">Tambah</button>
        </form>
    </div>
</div>