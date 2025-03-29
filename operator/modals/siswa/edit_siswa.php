<!-- Modal Edit -->
<div class="ui tiny modal" id="modalEdit">
    <div class="header">Edit Siswa</div>
    <div class="content">
        <form class="ui form" method="POST">
            <input type="hidden" name="ubah_siswa">
            <input type="hidden"id="nisLama" name="nis_lama" value="<?=$siswa['nis'];?>"> <!-- Tambahkan ini -->
            
            <div class="ui equal width fields">
                <div class="field">
                    <label>NIS</label>
                    <input type="number" id="editNis" value="<?=$siswa['nis'];?>" name="nis" required>
                </div>
                <div class="field">
                    <label>No Absen</label>
                    <input type="number" id="editAbsen" value="<?=$siswa['no_absen'];?>" name="no_absen" required>
                </div>
            </div>

            <div class="field">
                <label>Nama Siswa</label>
                <input type="text" id="editNama" name="nama_siswa" value="<?=$siswa['nama_siswa'];?>" required>
            </div>

            <div class="field">
                <label>Password Baru (Opsional)</label>
                <input type="password" id="editPassword" name="password" placeholder="Masukkan password baru jika ingin mengganti">
            </div>

            <div class="ui equal width fields">
                <div class="field">
                    <label>Nomor Telepon</label>
                    <input type="number" id="editTelp" value="<?=$siswa['no_telp'];?>" name="no_telp" required>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" id="editEmail" value="<?=$siswa['email'];?>" name="email" required>
                </div>
            </div>

            <div class="field">
                <label>Jurusan</label>
                <select id="editJurusan" name="id_jurusan" required>
                    <?php
                    $jurusan_result = mysqli_query($koneksi, "SELECT * FROM jurusan");
                    while ($jurusan = mysqli_fetch_array($jurusan_result)) {
                        $selected = ($jurusan['id_jurusan'] == $siswa['id_jurusan']) ? "selected" : "";
                        echo "<option value='{$jurusan['id_jurusan']}' $selected>{$jurusan['jurusan']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div style="margin-top: 20px;" class="ui equal width fields">
                <div class="field">
                    <label>Kelas</label>
                    <input type="text" id="editKelas" value="<?=$siswa['kelas'];?>" name="kelas" required>
                </div>
                <div class="field">
                    <label>Angkatan</label>
                    <input type="number" id="editAngkatan" value="<?=$siswa['angkatan'];?>" name="angkatan" required>
                </div>
            </div>

            <button class="ui fluid primary button" type="submit">Simpan</button>
        </form>
    </div>
</div>