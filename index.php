<?php
require 'koneksi/koneksi.php';

// Cek apakah user sudah login menggunakan cookies
if (!isset($_COOKIE['level_user'])) {
    header("Location: login.php");
    exit();
}

// Ambil level user dari cookies
$level_user = $_COOKIE['level_user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Globaliti SKKPd</title>
    <link rel="icon" href="assets/logo/logo.ico">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/fomantic/dist/semantic.min.css">
    <script src="assets/fomantic/dist/semantic.min.js"></script>
</head>
<body>

<!-- Tampilkan sidebar sesuai dengan role -->
<?php
if ($level_user == "operator") {
    include "sidebar/sidebar_admin.php";
} else {
    include "sidebar/sidebar_siswa.php";
}
?>

<div class="pusher">
    <?php
    // Menentukan folder berdasarkan role
    if ($level_user == "operator") {
        $folder = "operator";
        $allowed_pages = [
            'home' => 'home.php',
            'siswa' => 'siswa.php',
            'jurusan' => 'jurusan.php',
            'operator' => 'operator.php',
            'kegiatan' => 'kegiatan.php',
            'kategori' => 'kategori.php',
            'sertifikat' => 'sertifikat.php',
            'cek_sertifikat' => 'sertifikat/cek_sertifikat.php',
            'profile' => 'profile_operator.php'
        ];
    } else {
        $folder = "siswa";
        $allowed_pages = [
            'home' => 'home.php',
            'sertifikat' => 'sertifikat.php',
            'cek_sertifikat' => 'cek_sertifikat.php',
            'profile' => 'profile_siswa.php',
            'cetak' => 'cetak_sertifikat.php'
        ];
    }
    
    // Cek apakah page yang diminta tersedia dalam daftar allowed_pages
    if (isset($_GET['page']) && isset($allowed_pages[$_GET['page']])) {
        $page = $_GET['page'];
        include "$folder/{$allowed_pages[$page]}";
    } else {
        include "$folder/home.php"; // Halaman default jika tidak ada parameter
    }
    ?>
</div>

</body>
</html>
