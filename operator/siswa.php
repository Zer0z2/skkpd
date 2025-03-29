<?php
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

// Handle tambah siswa
if (isset($_POST['tambah_siswa'])) {
    $nis = $_POST['nis'];
    $absen = $_POST['no_absen'];
    $nama_siswa = $_POST['nama_siswa'];
    $id_jurusan = $_POST['id_jurusan'];
    $kelas = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];
    $no_telp = $_POST['no_telp'];
    $email = $_POST['email'];

    // Cek apakah NIS sudah ada di database
    $cek_nis = $koneksi->prepare("SELECT nis FROM siswa WHERE nis = ?");
    $cek_nis->bind_param("s", $nis);
    $cek_nis->execute();
    $cek_nis->store_result();
    
    if ($cek_nis->num_rows > 0) {
        echo "<script>alert('NIS sudah terdaftar! Gunakan NIS lain.'); window.history.back();</script>";
        exit();
    }
    $cek_nis->close();

    // Jika NIS belum ada, lanjutkan proses insert
    $stmt = $koneksi->prepare("INSERT INTO siswa (nis, no_absen, nama_siswa, no_telp, email, id_jurusan, kelas, angkatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nis, $absen, $nama_siswa, $no_telp, $email, $id_jurusan, $kelas, $angkatan);
    $stmt->execute();
    $stmt->close();

    // Tambahkan ke tabel pengguna
    $default_password = password_hash("123", PASSWORD_DEFAULT); // Hash password

    $stmt = $koneksi->prepare("INSERT INTO pengguna (nis, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $nis, $default_password);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Siswa berhasil ditambahkan!'); window.location.href='index.php?page=siswa';</script>";
    exit();
}

// Handle ubah siswa
if (isset($_POST['ubah_siswa'])) {
    $nis_lama = $_POST['nis_lama'] ?? ''; // Pastikan nis_lama ada
    $nis_baru = $_POST['nis'] ?? ''; 
    $absen = $_POST['no_absen'] ?? '';
    $nama_siswa = $_POST['nama_siswa'] ?? '';
    $id_jurusan = $_POST['id_jurusan'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $angkatan = $_POST['angkatan'] ?? '';
    $no_telp = $_POST['no_telp'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Pastikan NIS baru tidak sama dengan NIS lain yang sudah ada (kecuali miliknya sendiri)
    if ($nis_lama !== $nis_baru) {
        $cek_nis = $koneksi->prepare("SELECT nis FROM siswa WHERE nis = ? AND nis != ?");
        $cek_nis->bind_param("ss", $nis_baru, $nis_lama);
        $cek_nis->execute();
        $cek_nis->store_result();
        if ($cek_nis->num_rows > 0) {
            echo "<script>alert('NIS sudah terpakai!'); window.history.back();</script>";
            exit();
        }
        $cek_nis->close();
    }

    // Update data siswa
    $stmt = $koneksi->prepare("UPDATE siswa SET nis=?, no_absen=?, nama_siswa=?, id_jurusan=?, kelas=?, angkatan=?, no_telp=?, email=? WHERE nis=?");
    $stmt->bind_param("isssssiss", $nis_baru, $absen, $nama_siswa, $id_jurusan, $kelas, $angkatan, $no_telp, $email, $nis_lama);
    if (!$stmt->execute()) {
        die("Error: " . $stmt->error);
    }
    $stmt->close();

    // Update password jika diisi
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare("UPDATE pengguna SET password=? WHERE nis=?");
        $stmt->bind_param("ss", $hashed_password, $nis_baru);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Data berhasil diperbarui!'); window.location.href='index.php?page=siswa';</script>";
    exit();
}

// Handle hapus siswa
if (isset($_POST['hapus_nis'])) {
    $nis = $_POST['hapus_nis'];

    $stmt = $koneksi->prepare("DELETE FROM siswa WHERE nis=?");
    $stmt->bind_param("s", $nis);
    $stmt->execute();
    $stmt->close();

    echo "<script>window.location.href='index.php?page=siswa';</script>";
    exit();
}
?>
<style>
#siswa-table {
    max-height: 500px; /* Sesuaikan tinggi */
    overflow-y: auto; /* Mengaktifkan scroll jika tabel terlalu panjang */
    display: block; /* Agar scroll bisa diterapkan */
}
#siswa-table thead,
#siswa-table tbody {
    display: block;
    width: 100%;
}
#siswa-table tbody {
    max-height: 450px; /* Atur tinggi bagian body */
    overflow-y: auto;
}
</style>

<div class="main-content">
<div class="ui container">
    <div class="ui two column very relaxed grid">
		<div class="column">	
			<h1>Daftar Siswa</h1>		
		</div>
        <div class="column right aligned">
            <button class="ui labeled icon primary button" id="tambahModal">
                <i class="add icon"></i>Tambah
            </button>
        </div>
	</div>
    <div class="ui divider"></div>
    <div class="ui one column very relaxed grid">
        <div class="column">
			<!-- Filter dan Pencarian -->
            <div class="ui form">
    <div class="ui equal width fields">
        <div class="field">
            <label>Jurusan</label>
            <select id="filter-jurusan" class="ui dropdown">
                <option value="all">Semua Jurusan</option>
                <?php
                $jurusanQuery = mysqli_query($koneksi, "SELECT DISTINCT jurusan FROM jurusan");
                while ($row = mysqli_fetch_array($jurusanQuery)) {
                    echo "<option value='{$row['jurusan']}'>{$row['jurusan']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label>Angkatan</label>
            <select id="filter-angkatan" class="ui dropdown">
                <option value="all">Semua Angkatan</option>
                <?php
                $angkatanQuery = mysqli_query($koneksi, "SELECT DISTINCT angkatan FROM siswa ORDER BY angkatan DESC");
                while ($row = mysqli_fetch_array($angkatanQuery)) {
                    echo "<option value='{$row['angkatan']}'>{$row['angkatan']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="field">
            <label>Cari Nama</label>
            <input type="text" id="search-nama" placeholder="Cari nama siswa...">
        </div>
    </div>
</div>
    </div>
    </div>
    <div class="ui segment" style="max-height: 500px; overflow-y: auto;">
    <div class="ui three stackable cards">
        <?php
        $hasil = mysqli_query($koneksi, "SELECT siswa.*, jurusan.jurusan FROM siswa INNER JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan");
        while ($siswa = mysqli_fetch_array($hasil)) {
            echo "<div class='ui fluid card siswa-card' 
        data-jurusan='{$siswa['jurusan']}' 
        data-angkatan='{$siswa['angkatan']}'
        data-nama='{$siswa['nama_siswa']}'>

                    <div class='content'>
                        <div class='ui top attached blue label'><i class='id card outline icon'></i> {$siswa['nis']}</div>
                        <div class='header'>{$siswa['nama_siswa']}</div>
                        <div class='description'>
                            <p><i class='list ol icon'></i> <strong>Absen:</strong> {$siswa['no_absen']}</p>
                            <p><i class='graduation cap icon'></i> <strong>Kelas:</strong> {$siswa['jurusan']} - {$siswa['kelas']}</p>
                            <p><i class='calendar icon'></i> <strong>Angkatan:</strong> {$siswa['angkatan']}</p>
                        </div>
                    </div>
                    <div class='content'>
                        <div class='description'>
                            <p><i class='phone icon'></i> <strong>No Telepon:</strong> {$siswa['no_telp']}</p>
                            <p><i class='envelope icon'></i> <strong>Email:</strong> {$siswa['email']}</p>
                        </div>
                    </div>
                    <div class='extra content'>
                        <div class='ui two buttons'>
                            <button class='ui yellow button editBtn' 
                                data-nis='{$siswa['nis']}'
                                data-no_absen='{$siswa['no_absen']}'
                                data-nama='{$siswa['nama_siswa']}'
                                data-id_jurusan='{$siswa['id_jurusan']}'
                                data-kelas='{$siswa['kelas']}'
                                data-angkatan='{$siswa['angkatan']}'
                                data-no_telp='{$siswa['no_telp']}'
                                data-email='{$siswa['email']}'>
                                <i class='edit icon'></i> Edit
                            </button>
                            <button class='ui red button deleteBtn' data-nis='{$siswa['nis']}'>
                                <i class='trash icon'></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>";
        }
        ?>
    </div>
</div>
</div>

<?php
include "modals/siswa/tambah_siswa.php";
include "modals/siswa/edit_siswa.php";
?>

</div>
<script>
    $(document).ready(function() {
    $('#filter-jurusan, #filter-angkatan, #search-nama').on('input change', function() {
        var jurusan = $('#filter-jurusan').val().toLowerCase();
        var angkatan = $('#filter-angkatan').val().toLowerCase();
        var nama = $('#search-nama').val().toLowerCase();

        $('.siswa-card').each(function() {
            var rowJurusan = $(this).attr('data-jurusan').toLowerCase();
            var rowAngkatan = $(this).attr('data-angkatan').toLowerCase();
            var rowNama = $(this).attr('data-nama').toLowerCase();

            var matchJurusan = (jurusan === "all" || rowJurusan === jurusan);
            var matchAngkatan = (angkatan === "all" || rowAngkatan === angkatan);
            var matchNama = (nama === "" || rowNama.includes(nama));

            if (matchJurusan && matchAngkatan && matchNama) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#tambahModal').click(function() {
        $('#modalTambah').modal('show');
    });

    $('.editBtn').click(function() {
        $('#editNis').val($(this).data('nis'));
        $('#nisLama').val($(this).data('nis'));
        $('#editAbsen').val($(this).data('no_absen'));
        $('#editNama').val($(this).data('nama'));
        $('#editTelp').val($(this).data('no_telp'));
        $('#editEmail').val($(this).data('email'));
        $('#editJurusan').val($(this).data('id_jurusan'));
        $('#editKelas').val($(this).data('kelas'));
        $('#editAngkatan').val($(this).data('angkatan'));
        $('#modalEdit').modal('show');
    });

    $('.deleteBtn').click(function() {
        var nis = $(this).data('nis');
        if (confirm("Hapus siswa ini?")) {
            $.post("index.php?page=siswa", { hapus_nis: nis }, function(response) {
                location.reload(); // Refresh halaman setelah hapus
            });
        }
    });
});
</script>

