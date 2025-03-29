<?php
include "koneksi/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis = $_COOKIE['nis'];
    $password_baru = mysqli_real_escape_string($koneksi, $_POST['password_baru']);
    $konfirmasi_password = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

    // Validasi input
    if (empty($password_baru) || empty($konfirmasi_password)) {
        echo "<script>alert('Password tidak boleh kosong!');</script>";
    } elseif ($password_baru !== $konfirmasi_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        // Hash password
        $password_hash = password_hash($password_baru, PASSWORD_BCRYPT);
        
        // Update password di database
        $query = "UPDATE pengguna SET password = '$password_hash' WHERE nis = '$nis'";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            echo "<script>alert('Password berhasil diperbarui!'); window.location.href='index.php?page=profile';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan, coba lagi!');</script>";
        }
    }
}

$nis = $_COOKIE['nis'];
$query = "SELECT siswa.*, jurusan.jurusan FROM siswa
          JOIN pengguna ON siswa.nis = pengguna.nis
          JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan
          WHERE siswa.nis = '$nis'";
$hasil = mysqli_query($koneksi, $query);
$profile = mysqli_fetch_array($hasil);
?>

<div class="main-content">
<div class="ui container">
        <div class="ui two column stackable grid">
            <!-- Detail Siswa -->
            <div class="column">
                <div class="ui segment">
                    <div class="ui top attached blue large label">Detail Siswa</div>
                    <table class="ui celled very basic table">
                        <tbody>
                            <tr>
                                <td class="four wide"><strong><i class="id card icon"></i> NIS</strong></td>
                                <td><?php echo $profile['nis'] ?></td>
                            </tr>
                            <tr>
                                <td class="four wide"><strong><i class="user icon"></i> Nama</strong></td>
                                <td><?php echo $profile['nama_siswa'] ?></td>
                            </tr>
                            <tr>
                                <td class="four wide"><strong><i class="list ol icon"></i> No Absen</strong></td>
                                <td><?php echo $profile['no_absen'] ?></td>
                            </tr>
                            <tr>
                                <td class="four wide"><strong><i class="phone icon"></i> No Telp</strong></td>
                                <td><?php echo $profile['no_telp'] ?></td>
                            </tr>
                            <tr>
                                <td class="four wide"><strong><i class="envelope icon"></i> Email</strong></td>
                                <td><?php echo $profile['email'] ?></td>
                            </tr>
                            <tr>
                                <td class="four wide"><strong><i class="graduation cap icon"></i> Kelas</strong></td>
                                <td><?php echo $profile['jurusan'] ?> <?php echo $profile['kelas'] ?></td>
                            </tr>
                            <tr>
                                <td class="four wide"><strong><i class="calendar icon"></i> Angkatan</strong></td>
                                <td><?php echo $profile['angkatan'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Ganti Password -->
            <div class="column">
                <div class="ui segment">
                    <div class="ui top attached orange large label">Ganti Password</div>
                    <form class="ui form" method="POST" action="">
                        <div class="field">
                            <label><i class="lock icon"></i> Ganti Password</label>
                            <input type="password" name="password_baru" placeholder="Masukkan password baru" required>
                        </div>
                        <div class="field">
                            <label><i class="lock icon"></i> Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" placeholder="Konfirmasi password baru" required>
                        </div>
                        <button class="ui yellow fluid button" type="submit">
                            <i class="sync icon"></i> Perbarui
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>