    <!-- Modal Edit -->
    <div class="ui tiny modal" id="modalEdit">
        <div class="header">Edit Kegiatan</div>
        <div class="content">
            <form class="ui form" method="POST">
                <input type="hidden" name="ubah_kegiatan">
                <input type="hidden" name="id_kegiatan" id="editId">
            <div class="field">
                <label>Jenis Kegiatan</label>
                <input type="text" id="editKegiatan" name="jenis_kegiatan" value="<?=$kegiatan['jenis_kegiatan'];?>" required>
            </div>
            <div class="field">
                <label>Angka Kredit</label>
                <input type="text" id="editKredit" name="angka_kredit" value="<?=$kegiatan['angka_kredit'];?>" required>
            </div>
            <div class="field">
                    <label>Kategori</label>
                    <div class="ui selection dropdown" id="editKategoriDropdown">
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
                <button class="ui fluid primary button" type="submit">Simpan</button>
            </form>
        </div>
        </div>