<!-- Modal Tambah -->
<div class="ui tiny modal" id="modalTambah">
    <div class="header">Tambah Siswa</div>
    <div class="content">
        <form class="ui form" method="POST">
            <input type="hidden" name="tambah_siswa">
            <div class="ui equal width fields">
            <div class="field">
                <label>NIS</label>
                <input type="number" placeholder="Masukkan NIS" name="nis" required>
            </div>
            <div class="field">
                <label>No Absen</label>
                <input type="number" placeholder="Masukkab Nomor Absen" name="no_absen" required>
            </div>
        </div>
        <div class="field">
            <label>Nama Siswa</label>
            <input type="text" name="nama_siswa" placeholder="Masukkan Nama Siswa" required>
        </div>
        <div class="ui equal width fields">
            <div class="field">
                <label>Nomor Telepon</label>
                <input type="number" placeholder="Masukkan Nomor Telepon" name="no_telp" required>
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" placeholder="Masukkan Email" name="email" required>
            </div>
        </div>
            <div class="field">
                <label>Jurusan</label>
                <select name="id_jurusan" required>
                    <?php
                    $jurusan_result = mysqli_query($koneksi, "SELECT * FROM jurusan");
                    while ($jurusan = mysqli_fetch_array($jurusan_result)) {
                        echo "<option value='{$jurusan['id_jurusan']}'>{$jurusan['jurusan']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div style="margin-top: 20px;" class="ui equal width fields">
            <div class="field">
                <label>Kelas</label>
                <input type="number" placeholder="Masukkan Kelas" name="kelas" required>
            </div>
            <div class="field">
                <label>Angkatan</label>
                <input type="number" placeholder="Masukkan Angkatan" name="angkatan" required>
            </div>
        </div>
            <button class="ui fluid primary button" type="submit">Tambah</button>
        </form>
    </div>
</div>