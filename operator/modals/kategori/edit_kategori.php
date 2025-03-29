<!-- Modal Edit -->
<div class="ui tiny modal" id="modalEdit">
    <div class="header">Edit Kategori</div>
    <div class="content">
        <form class="ui form" method="POST">
            <input type="hidden" name="ubah_kategori">
            <input type="hidden" name="id_kategori" id="editId">
        <div class="field">
            <label>Kategori</label>
            <select name="kategori" id="editKategori" class="ui fluid dropdown" required>
                <option value="">Pilih Kategori</option>
                <option value="Wajib" <?= (isset($kategori['kategori']) && $kategori['kategori'] == 'Wajib') ? 'selected' : '' ?>>Wajib</option>
                <option value="Optional" <?= (isset($kategori['kategori']) && $kategori['kategori'] == 'Optional') ? 'selected' : '' ?>>Optional</option>
            </select>
        </div>
        <div class="field">
            <label>Sub Kategori</label>
            <input type="text" id="editSub" name="sub_kategori" value="<?=$kategori['sub_kategori'];?>" required>
        </div>
            <button class="fluid ui primary button" type="submit">Simpan</button>
        </form>
    </div>
</div>