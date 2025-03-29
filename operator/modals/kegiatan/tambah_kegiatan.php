<!-- Modal Tambah -->
<div class="ui tiny modal" id="modalTambah">
    <div class="header">Tambah Kegiatan</div>
    <div class="content">
        <form class="ui form" method="POST">
            <input type="hidden" name="tambah_kegiatan">
        <div class="field">
            <label>Jenis Kegiatan</label>
            <input type="text" name="jenis_kegiatan" placeholder="Masukkan Jenis Kegiatan" required>
        </div>
        <div class="field">
            <label>Angka Kredit</label>
            <input type="number" name="angka_kredit" placeholder="Masukkan Angka Kredit" required>
        </div>
        <div class="field">
            <label>Kategori</label>
            <div class="ui selection dropdown">
                <input type="hidden" name="id_kategori" required>
                <i class="dropdown icon"></i>
                <div class="default text">Pilih Kategori</div>
                <div class="menu">
                    <?php
                    $kategori_result = mysqli_query($koneksi, "SELECT * FROM kategori");
                    while ($kategori = mysqli_fetch_array($kategori_result)) {
                        echo "<div class='item' data-value='{$kategori['id_kategori']}'>{$kategori['kategori']} - {$kategori['sub_kategori']}</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <button class="ui fluid primary button" type="submit">Tambah</button>
        </form>
    </div>
</div>