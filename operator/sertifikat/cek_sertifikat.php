<?php
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

// Mengambil parameter dari URL dengan validasi
$pdfFile = isset($_GET['file']) ? htmlspecialchars($_GET['file']) : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$pdfFile || !$id) {
    die("❌ File PDF atau ID sertifikat tidak ditemukan!");
}

// Cek apakah file sertifikat benar-benar ada
$filePath = $_SERVER['DOCUMENT_ROOT'] . "/skkpd/sertifikat/" . $pdfFile;
if (!file_exists($filePath)) {
    die("❌ File sertifikat tidak ditemukan!");
}

// Query untuk mendapatkan detail sertifikat & siswa
$query = "SELECT nama_siswa, nis, jurusan, kelas, no_telp, email, angkatan, kategori, sub_kategori, jenis_kegiatan, status, catatan 
          FROM sertifikat 
          INNER JOIN kegiatan USING(id_kegiatan) 
          INNER JOIN kategori USING(id_kategori) 
          INNER JOIN siswa USING(nis) 
          INNER JOIN jurusan USING(id_jurusan) 
          WHERE id_sertifikat = '$id'";

$result = mysqli_query($koneksi, $query);
if (!$result) {
    die("❌ Query error: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($result);
$tgl = date("Y-m-d");
// Proses update status sertifikat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tombol_submit'])) {
    $status = $_POST['status'];
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, $_POST['catatan']) : NULL;

    $updateQuery = "UPDATE sertifikat SET 
                    catatan = " . ($status === 'Tidak Valid' ? "'$catatan'" : "NULL") . ", 
                    status = '$status', 
                    tgl_status_berubah = '$tgl' 
                    WHERE id_sertifikat='$id'";

    if (mysqli_query($koneksi, $updateQuery)) {
        echo "<script>alert('✅ Data berhasil diperbarui');window.location.href='index.php?page=sertifikat'</script>";
    } else {
        echo "<script>alert('❌ Gagal memperbarui data!');window.location.href='index.php?page=cek_sertifikat&id=$id&file=$pdfFile'</script>";
    }
}
?>

<style>
    .detail.segment {
        max-height: 635px; /* Sesuaikan tinggi sesuai kebutuhan */
        overflow-y: auto;
    }
</style>

<div class="main-content">
    <div class="ui two column grid">
        <!-- Kolom PDF Sertifikat -->
        <div class="column">
            <h2 class="ui small header"><i class="certificate icon"></i> Sertifikat</h2>
            <div class="ui segment">
                <embed src="/skkpd/sertifikat/<?= urlencode($pdfFile) ?>" type="application/pdf" width="100%" height="600px">
            </div>
        </div>

        <!-- Kolom Detail & Validasi dalam Satu Segment -->
        <div class="column">
            <h2 class="ui small header"><i class="info circle icon"></i> Detail Sertifikat</h2>
            <div class="ui detail segment">
                <h3 class="ui dividing header"><i class="user icon"></i> Detail Siswa</h3>
                <table class="ui very basic table">
                    <tbody>
                        <tr><td><strong>Nama</strong></td><td><?= htmlspecialchars($data["nama_siswa"]) ?></td></tr>
                        <tr><td><strong>NIS</strong></td><td><?= htmlspecialchars($data["nis"]) ?></td></tr>
                        <tr><td><strong>Kelas</strong></td><td><?= htmlspecialchars($data["jurusan"] . " " . $data["kelas"]) ?></td></tr>
                        <tr><td><strong>Telepon</strong></td><td><?= htmlspecialchars($data["no_telp"]) ?></td></tr>
                        <tr><td><strong>Email</strong></td><td><?= htmlspecialchars($data["email"]) ?></td></tr>
                        <tr><td><strong>Angkatan</strong></td><td><?= htmlspecialchars($data["angkatan"]) ?></td></tr>
                    </tbody>
                </table>

                <h3 class="ui dividing header"><i class="folder icon"></i> Detail Kegiatan</h3>
                <table class="ui very basic table">
                    <tbody>
                        <tr><td><strong>Kategori</strong></td><td><?= htmlspecialchars($data["kategori"]) ?></td></tr>
                        <tr><td><strong>Sub Kategori</strong></td><td><?= htmlspecialchars($data["sub_kategori"]) ?></td></tr>
                        <tr><td><strong>Kegiatan</strong></td><td><?= htmlspecialchars($data["jenis_kegiatan"]) ?></td></tr>
                    </tbody>
                </table>

                <!-- Status Validasi -->
                <?php if ($data["status"] == "Menunggu Validasi") { ?>
                <h3 class="ui dividing header"><i class="check circle icon"></i> Validasi</h3>
                <div class="ui buttons">
                    <form action="" method="POST">
                        <input type="hidden" name="status" value="Valid">
                        <button class="ui green button" type="submit" name="tombol_submit">
                            <i class="check icon"></i> Valid
                        </button>
                    </form>
                    <div class="or"></div>
                    <button class="ui red button" onclick="toggleInvalid()">
                        <i class="times icon"></i>Tidak Valid
                    </button>
                </div>
                <div class="ui hidden divider"></div>
                    <form action="" method="POST" id="invalid-form" style="display: none;" class="ui form">
                        <div class="field">
                            <label>Catatan</label>
                            <textarea name="catatan" placeholder="Tulis catatan di sini..."></textarea>
                        </div>
                        <input type="hidden" name="status" value="Tidak Valid">
                        <button class="ui red button" type="submit" name="tombol_submit">
                            <i class="send icon"></i> Kirim
                        </button>
                        <button class="ui button" type="button" onclick="cancelInvalid()">Batal</button>
                    </form>
                    <?php } elseif ($data["status"] == "Tidak Valid") { ?>
                        <div class="ui segment">
                            <h4><i class="sticky note icon"></i> Catatan</h4>
                            <p><?= htmlspecialchars($data["catatan"]) ?></p>
                        </div>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    function toggleInvalid() {
        document.getElementById("invalid-form").style.display = "block";
    }

    function cancelInvalid() {
        document.getElementById("invalid-form").style.display = "none";
    }
</script>