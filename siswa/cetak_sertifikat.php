<?php
require $_SERVER['DOCUMENT_ROOT'] . "/skkpd/assets/export/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Aktifkan output buffering untuk menghindari masalah header
ob_start();

// Koneksi ke database
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

$nis = $_COOKIE['nis'];
$query = "SELECT nama_siswa FROM siswa WHERE nis = '$nis'";
$result = mysqli_query($koneksi, $query);
$siswa = mysqli_fetch_assoc($result);
$nama_siswa = $siswa['nama_siswa'] ?? 'Nama Tidak Diketahui';

// Konfigurasi Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Pastikan path gambar bisa diakses
$imgData = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/skkpd/assets/sertifikat/background-sertif.jpeg"));
$backgroundUrl = 'data:image/jpeg;base64,' . $imgData;

// HTML Sertifikat
$html = '<html>
<head>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { 
            background-image: url("' . $backgroundUrl . '"); 
            background-size: cover; 
            text-align: center; 
            font-family: Arial, sans-serif; 
        }
        .nama { font-size: 30px; font-weight: bold; margin-top: 370px; }
    </style>
</head>
<body>
    <div class="nama">' . htmlspecialchars($nama_siswa) . '</div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Hentikan output buffering sebelum streaming PDF
ob_end_clean();
$dompdf->stream("Sertifikat_$nis.pdf", ["Attachment" => 0]);
?>
