<?php
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

// Mengambil parameter dari URL dengan validasi
$pdfFile = isset($_GET['file']) ? htmlspecialchars($_GET['file']) : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$pdfFile || !$id || !preg_match('/\.pdf$/', $pdfFile)) {
    die("❌ File PDF atau ID sertifikat tidak ditemukan!");
}

// Cek apakah file sertifikat benar-benar ada
$filePath = $_SERVER['DOCUMENT_ROOT'] . "/skkpd/sertifikat/" . $pdfFile;
if (!file_exists($filePath)) {
    die("❌ File sertifikat tidak ditemukan!");
}

// Query untuk mendapatkan detail sertifikat & siswa
$tgl = date("Y-m-d");
$query = "SELECT nama_siswa, nis, jurusan, kelas, no_telp, email, angkatan, kategori, sub_kategori, jenis_kegiatan, status, catatan 
          FROM sertifikat 
          INNER JOIN kegiatan USING(id_kegiatan) 
          INNER JOIN kategori USING(id_kategori) 
          INNER JOIN siswa USING(nis) 
          INNER JOIN jurusan USING(id_jurusan) 
          WHERE id_sertifikat = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("❌ Data sertifikat tidak ditemukan!");
}

// Proses unggah ulang sertifikat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_certificate'])) {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/skkpd/sertifikat/";
    $newFileName = "sertifikat_" . time() . ".pdf";
    $uploadFilePath = $uploadDir . $newFileName;
    
    if (move_uploaded_file($_FILES['new_certificate']['tmp_name'], $uploadFilePath)) {
        $updateQuery = "UPDATE sertifikat SET sertifikat = ?, status = 'Menunggu Validasi', tgl_status_berubah = ? WHERE id_sertifikat = ?";
        $stmt = mysqli_prepare($koneksi, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssi", $newFileName, $tgl, $id);
        mysqli_stmt_execute($stmt);
        echo "<script>alert('✅ Sertifikat berhasil diperbarui!');window.location.href='index.php?page=sertifikat'</script>";
    } else {
        echo "<script>alert('❌ Gagal mengunggah sertifikat baru!');</script>";
    }
}
?>

<div class="main-content">
    <div class="ui two column grid">
        <div class="column">
            <h2 class="ui header"><i class="certificate icon"></i>Sertifikat</h2>
            <div class="ui segment">
                <embed src="/skkpd/sertifikat/<?= urlencode($pdfFile) ?>" type="application/pdf" width="100%" height="600px">
            </div>
        </div>
        <div class="column">
            <h2 class="ui header"><i class="thumbtack icon"></i>Detail Sertifikat</h2>
            <div class="ui segment">
                <table class="ui celled table">
                    <tbody>
                        <tr><td><strong>Kategori</strong></td><td><?= htmlspecialchars($data["kategori"]) ?></td></tr>
                        <tr><td><strong>Sub Kategori</strong></td><td><?= htmlspecialchars($data["sub_kategori"]) ?></td></tr>
                        <tr><td><strong>Kegiatan</strong></td><td><?= htmlspecialchars($data["jenis_kegiatan"]) ?></td></tr>
                    </tbody>
                </table>
                
                <?php if ($data["status"] == "Tidak Valid") { ?>
                    <div class="ui header"><i class="sticky note icon"></i>Catatan</div>
                    <div class="ui segment">
                        <div><?= htmlspecialchars($data["catatan"]) ?></div>
                    </div>
                    <div class="ui header"><i class="edit icon"></i> Perbarui Sertifikat</div>
                    <form action="" method="POST" enctype="multipart/form-data" class="ui form">
                        <div class="field">
                            <label>Unggah Sertifikat Baru (PDF)</label>
                            <input type="file" name="new_certificate" accept="application/pdf" required>
                        </div>
                        <button class="ui blue button" type="submit"><i class="file import icon"></i>Unggah Sertifikat</button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
