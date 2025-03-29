<?php
require $_SERVER['DOCUMENT_ROOT'] . "/skkpd/assets/export/vendor/autoload.php";
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Konfigurasi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// **Memuat logo sebagai base64 jika tidak muncul**
$logoPath = $_SERVER['DOCUMENT_ROOT'] . "/skkpd/assets/image/logoti.png";
if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoSrc = 'data:image/png;base64,' . $logoData;
} else {
    $logoSrc = ''; // Jika file tidak ditemukan, kosongkan
}

// **Mulai membuat HTML untuk PDF**
$html = '
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }

    .header {
        width: 100%;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        display: table;
        text-align: center;
    }
    .logo {
        display: table-cell;
        width: 100px;
        text-align: center;
        vertical-align: middle;
    }
    .logo img {
        width: 80px;
        height: auto;
    }
    .logo2 {
        visibility: hidden;
        display: table-cell;
        width: 100px;
        text-align: center;
        vertical-align: middle;
    }
    .logo2 img {
        width: 80px;
        height: auto;
    }
    .text {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
    }
    h1 { font-size: 20px; margin: 5px 0; }
    p { margin: 3px 0; font-size: 13px; }
    a { color: blue; text-decoration: none; }
</style>


<div class="header">
    <div class="logo">
        <img src="' . $logoSrc . '" alt="Logo">
    </div>
    <div class="text">
        <h1>SMK TI Bali Global Denpasar</h1>
        <p>Jl. Tukad Citarum No. 44 Denpasar, Bali</p>
        <p>Website: <a href="https://smkti-baliglobal.sch.id">https://smkti-baliglobal.sch.id</a> | 
        Email: <a href="mailto:info@smkti-baliglobal.sch.id">info@smkti-baliglobal.sch.id</a></p>
    </div>
    <div class="logo2"><img src="' . $logoSrc . '" alt="Logo"></div>
</div>';

// **Menampilkan Data Sertifikat Siswa**
$html .= '<h2 style="text-align:center;">Data Sertifikat Siswa</h2>';

// Ambil daftar angkatan dari database
$query_angkatan = "SELECT DISTINCT angkatan FROM siswa ORDER BY angkatan ASC";
$result_angkatan = mysqli_query($koneksi, $query_angkatan);

while ($row_angkatan = mysqli_fetch_array($result_angkatan)) {
    $angkatan_saat_ini = $row_angkatan['angkatan'];
    
    $html .= "<h3 style='margin-top:20px;'>Angkatan: {$angkatan_saat_ini}</h3>";
    $html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
                <tr style="background-color: #f2f2f2;">
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kegiatan</th>
                    <th>Status</th>
                    <th>Kelas</th>
                </tr>';

    // Query untuk mengambil data sertifikat berdasarkan angkatan
    $query_siswa = "SELECT siswa.nis, siswa.nama_siswa, kegiatan.jenis_kegiatan, sertifikat.status, siswa.kelas, jurusan.jurusan
                    FROM sertifikat
                    INNER JOIN siswa USING(nis)
                    INNER JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan
                    INNER JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan
                    WHERE siswa.angkatan = '$angkatan_saat_ini'";

    $result_siswa = mysqli_query($koneksi, $query_siswa);
    $no = 1;
    while ($data = mysqli_fetch_array($result_siswa)) {
        $html .= "<tr>
                    <td>{$no}</td>
                    <td>{$data['nis']}</td>
                    <td>{$data['nama_siswa']}</td>
                    <td>{$data['jenis_kegiatan']}</td>
                    <td>{$data['status']}</td>
                    <td>{$data['jurusan']} {$data['kelas']}</td>
                  </tr>";
        $no++;
    }

    if ($no == 1) {
        $html .= "<tr><td colspan='6' style='text-align:center;'>Data tidak ditemukan</td></tr>";
    }
    
    $html .= '</table>';
}

// **Rekap Jenis Kegiatan Sertifikat**
$html .= '<h2 style="text-align:center;">Rekap Jenis Kegiatan Sertifikat</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
            <tr style="background-color: #f2f2f2;">
                <th>Jenis Kegiatan</th>
                <th>Total Sertifikat</th>
            </tr>';

$query_rekap = "SELECT kegiatan.jenis_kegiatan, COUNT(*) as total 
                FROM sertifikat
                INNER JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan
                GROUP BY kegiatan.jenis_kegiatan";

$result_rekap = mysqli_query($koneksi, $query_rekap);
while ($row = mysqli_fetch_array($result_rekap)) {
    $html .= "<tr>
                <td>{$row['jenis_kegiatan']}</td>
                <td>{$row['total']}</td>
              </tr>";
}
$html .= '</table>';

// **Load HTML ke Dompdf**
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// **Output PDF ke browser tanpa mengunduh otomatis**
$dompdf->stream("laporan.pdf", array("Attachment" => 0));
?>
